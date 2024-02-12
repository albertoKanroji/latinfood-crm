const User = require("../models/user");
const bcrypt = require("bcryptjs");
const jwt = require("jsonwebtoken");
const keys = require("../config/keys");
module.exports = {

  login(req, res) {
    const email = req.body.email;
    const password = req.body.password;
  
    User.findByEmail(email, async (err, myUser) => {
      if (err) {
        return res.status(501).json({
          success: false,
          message: 'Hubo un error con el usuario',
          error: err
        });
      }
  
      if (!myUser) {
        return res.status(401).json({
          success: false,
          message: 'El email no fue encontrado'
        });
      }
  
      const hashWith2a = myUser.password;
  
     // console.log('Contraseña almacenada en la base de datos:', hashWith2a);
     // console.log('Contraseña ingresada por el usuario:', password);
      
      try {
        // Utilizamos trim() para eliminar cualquier espacio en blanco que pueda existir en las contraseñas
        const isPasswordValid = bcrypt.compareSync(password.trim(), hashWith2a.trim());
        
     //   console.log('¿Las contraseñas coinciden?', isPasswordValid);
  
        if (isPasswordValid) {
          const token = jwt.sign({ id: myUser.id, email: myUser.email }, keys.secretOrKey, {});
  
          const data = {
            id: myUser.id,
            name: myUser.name,
            last_name: myUser.last_name,
            last_name2: myUser.last_name2,
            email: myUser.email,
            address: myUser.address,
            phone: myUser.phone,
            session_token: `JWT ${token}`
          };
  
          return res.status(201).json({
            success: true,
            message: 'El usuario fue autenticado',
            data: data
          });
        } else {
          return res.status(401).json({
            success: false,
            message: 'La contraseña es incorrecta'
          });
        }
      } catch (error) {
        console.error('Error en el proceso de autenticación:', error);
        return res.status(500).json({
          success: false,
          message: 'Hubo un error en el proceso de autenticación',
          error: error
        });
      }
    });
  } ,

    register(req, res) {

        const user = req.body; // CAPTURO LOS DATOS QUE ME ENVIE EL CLIENTE

            // Verifica si no se proporciona una imagen, y si es así, asigna una imagen predeterminada
    if (!user.image || user.image.trim() === "") {
        user.image = "https://firebasestorage.googleapis.com/v0/b/miigrup.appspot.com/o/descarga.png?alt=media&token=cfa75f40-0d8b-4f94-a68c-2bee39a150d6"; // Cambia esto con la URL real
    }
        User.create(user, (err, data) => {

            if (err) {
                return res.status(501).json({
                    success: false,
                    message: 'Hubo un error con el registro del usuario',
                    error: err
                });
            }

            return res.status(201).json({
                success: true,
                message: 'El registro se realizo correctamente',
                data: data // EL ID DEL NUEVO USUARIO QUE SE REGISTRO
            });

        });

    }

}
