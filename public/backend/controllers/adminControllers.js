const User = require("../models/admin");
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
     // console.log('Contraseña ingresada por el admin:', password);
      
      try {
        // Utilizamos trim() para eliminar cualquier espacio en blanco que pueda existir en las contraseñas
        const isPasswordValid = bcrypt.compareSync(password.trim(), hashWith2a.trim());
        
     //   console.log('¿Las contraseñas coinciden?', isPasswordValid);
  
        if (isPasswordValid) {
          const token = jwt.sign({ id: myUser.id, email: myUser.email }, keys.secretOrKey, {});
  
          const data = {
            id: myUser.id,
            name: myUser.name,
            email: myUser.email,
            phone: myUser.phone,
            session_token: `JWT ${token}`
          };
  
          return res.status(201).json({
            success: true,
            message: 'El admin fue autenticado',
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
  } 

 

}
