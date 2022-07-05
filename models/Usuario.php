<?php 

// elegimos model ya que en composer elegimos la carpeta
namespace Model;

class Usuario extends ActiveRecord{

    //indicamos la tabla de mysql
    protected static $tabla = 'usuarios';
    // mandamos el arreglo a active record
    protected static $columnasDB = ['id','nombre', 'apellido', 'email', 'telefono','password', 'admin', 'confirmado', 'token'];

    // Creamos los atributos para cada campo
    // al ser public podemos acceder a ellos, ya sea en la clase misma o en el objeto una vez que sea istanciado
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    // definimos nuestro constructor
    // lo utilizamos para darle valor a los atributos, es el 1er metodo que se ejecuta al crear el objeto y se llama automaticamente
    // tomará unos argumentos pero por el momento sera un array vacio
    public function __construct($args = []){
        // será un arreglo asociativo y el (??) sera en caso de no estar lleno tendra un valor para poder verificar si un campo esta vacio o no
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }

    public function validarNuevaCuenta(){
         if(!$this->nombre){
            //  self hace referencia a la clase ya que biene de active Record
             self::$alertas['error'][] = 'El Nombre es Obligatorio';
         }
         if(!$this->apellido){
            //  self hace referencia a la clase ya que biene de active Record
             self::$alertas['error'][] = 'El Apellido es Obligatorio';
         }
         if(!$this->telefono){
            //  self hace referencia a la clase ya que biene de active Record
             self::$alertas['error'][] = 'El Telefono es Obligatorio';
         }
         if(!$this->email){
            //  self hace referencia a la clase ya que biene de active Record
             self::$alertas['error'][] = 'El Email es Obligatorio';
         }
         if(!$this->password){
            //  self hace referencia a la clase ya que biene de active Record
             self::$alertas['error'][] = 'La Contraseña es Obligatoria';
         }
         if(strlen($this->password < 6)){
            self::$alertas['error'][] = 'La Contraseña debe contener al menos 6 caracteres';
         }

        //  retornamos el mensaje al arreglo de alertas
         return self::$alertas;
    }

    public function validarLogin(){
        // $this hacemos referncia a la instancia con los datos guardados de Post 
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        // retornamos al controlador
        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    //revisa si el usuario ya existe por medio del gmail
    public function existeUsuario(){
        // el self tabla hacer referencia al nombre de la tabla 
        // el this para acceder al elemento de usuario pero como estamos en la clase creada se usa this ya que los datos fueron guardados en loginController
        // si estuviera en login entonces se pondria $login por que haya se instancio pero como esta dentro de la clase se utiliza this 
        // como es objeto se accede con flecha
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email ."' LIMIT 1";
        // debuguear($query);
        $resultado = self::$db->query($query);

        // si encuentra un resultado es por que esa personsa esta registrada
        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya está registrado';
        }
        return $resultado;
        // debuguear($resultado);
    }

    public function hashPassword(){
        // toma 2 valores el password y el metodo de hash
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        // accedemos a ese elemento
        // uniqid no da un id aleatorio y unico     
        $this->token = uniqid();
    }

    public function comprobarPasswordYVerificado($password){
        // comprobar que el password este correcto 
        // this->email se guarda en memoria de active record por medio de LoginController Usuario::Where
        $resultado = password_verify($password, $this->password);

        // comprobar que el usuario este confirmado 
        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Password Incorrecto o tu cuenta no ha sido confirmada';
        }else {
            return true;
        }
        debuguear($resultado);
    }

}

