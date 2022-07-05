<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    // para mostrar la vista debemos tener la instancias creada y esta en el index.php Router $router
    public static function login(Router $router){
        // echo "desde login";

        $alertas = [];

        // Para recordar datos del post  php echo s($auth->email);
        $auth = new Usuario();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // instanciamos el modelo de usuario y tendra lo que el usuario escribio
            $auth = new Usuario($_POST);

            // validamos que los campos no esten vacios
            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                // echo "Usuario Agrego tanto email como password";
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email',$auth->email);
                
                if($usuario){
                    // verificar el password
                    // pasamos lo que el usuario escribio en el formulario
                    if($usuario->comprobarPasswordYVerificado($auth->password)){
                        if(!isset($_SESSION)){
                            session_start();
                        }
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . "  " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        // debuguear($usuario->admin);
                        if($usuario->admin === "1"){
                            // debuguear('Es admin');
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else{
                            // debuguear('Es cliente');
                            header('Location: /cita');
                        }
                        // debuguear($_SESSION);
                    };
                    // debuguear($usuario);
                } else {
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }

        }
        $alertas = Usuario::getAlertas();
        // render toma una vista y datos en forma de arreglo y pasa el arreglo
        $router->render('auth/login',[
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }
    public static function logout(){
        echo "desde logout";
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    
    public static function olvide(Router $router){
        // echo "desde olvide";
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            // debuguear($auth);
            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){
                    // si ya existe y esta confirmado
                    // Creamos y guardamos el token 
                    $usuario->crearToken();
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Enviar el email  con el token
                    Usuario::setAlerta('exito', 'Revisa tu Email');
                //    debuguear($usuario);

                } else {
                    // no exite o no confirmado 
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
                // debuguear($usuario);
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);

    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        // accedemos al token de la url 
        $token = s($_GET['token']);

        // buscar usuario por su token 
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Leer el nuevo password
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();
            // debuguear($password);

            // si pasamos la validacion
            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                // aqui eliminamos el token 
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
                // debuguear($usuario);
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
        echo "desde recuperar";
    }
    public static function crear(Router $router){
        // echo "desde crear";
        // instanciamos usuario, automaticamente importará el MODEL->usuario
        $usuario = new Usuario;

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // echo "enviasate form";

            // Traemos los datos de crear-cuenta ya sanitizados
            $usuario->sincronizar($_POST);
            // validamos que los campos no tengan errores desde usuario.php 
            $alertas = $usuario->validarNuevaCuenta();
            // debuguear($usuario);
            
            // Revisar que alertas este vacio
            if(empty($alertas)){
                // echo 'Pasaste la validacion';
                //si no har errores, revisar que el usuario no este registrado mediante su email
                // como es objeto se usa flecha
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    // se pone usuario ya que alertas estan como protected y static
                    $alertas = Usuario::getAlertas();
                } else{
                    // No esta registrado
                    // se procede a encryptar el passwrod
                    $usuario->hashPassword();

                    //generar token unico al gmail ingresado
                    $usuario->crearToken();

                    // enviar el email
                    // para acceder a la clase de la carpeta classes debemos instanciar
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);

                    // debuguear($email);
                    $email->enviarConfirmacion();

                    //creamos el ususario
                    $resultado = $usuario->guardar();

                    // debuguear($usuario);

                    if($resultado){
                        header('Location: /mensaje');
                    }
                    
                }
            }

        }

        // con el metodo render vamos a mandar hacia la vista los datos, una vez que se llenen los campos y algunos son 
        // correctos los deje
        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        // validamos que los campos no tengan errores desde usuario.php 
        $alertas = [];

        // accedemos al token de la url 
        $token = s($_GET['token']);
        // mostramos el token 
        // debuguear(($token));
        // se pone :: por que en active record esta como static function
        // y mandamos la columna a busacar en sql y el id del token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            // Mostrar mensaje de error
            echo "Token no valido";
            // como es estatico no requiero instanciarlo
            Usuario::setAlerta('error','Token no valido');
        } else {
            // Modificar a usuario confirmado
            echo "Token valido, confirmando usuario";
            // cambiaro el valor del POST
            $usuario->confirmado= "1";
            $usuario->token = null;
            // debuguear($usuari    o);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            // indicamos lo que le enviaremos al archivo
            'alertas' => $alertas
        ]);
    }

}

?>