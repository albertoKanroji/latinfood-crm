const db = require("../config/config");
const bcrypt = require("bcrypt-nodejs");
const User = {};

User.findById = (id, result) => {
  const sql = `SELECT 
    name,
last_name,
last_name2,
email,
password,
address,
phone,
image
WHERE id =?
`;
  db.query(sql, [id], (err, user) => {
    if (err) {
      console.error(err);
      result(err, null);
    } else {
      console.log("User: " + user);
      result(null, user);
    }
  });
};
User.findByEmail = (email, result) => {
    const sql = `
      SELECT
      id,
        name,
        last_name,
        last_name2,
        email,
        password,
        address,
        phone,
        image
      FROM
        customers
      WHERE
        email = ?
    `;
  
    db.query(sql, [email], (err, user) => {
      if (err) {
        console.log('Error:', err);
        result(err, null);
      } else {
        try {
          //console.log('USUARIO', user[0]);
          result(null, user[0]);
        } catch (error) {
          console.log('Error al obtener el usuario:', error);
          result(error, null);
        }
      }
    });
  };
  
User.create = async (user, result) => {
    const salt = bcrypt.genSaltSync(10);
    const hash = bcrypt.hashSync(user.password, salt);
    const hashWith2y = hash.replace(/^\$2a/, "$2y");
  
    const sql = `INSERT INTO customers (
      name,
      last_name,
      last_name2,
      email,
      password,
      address,
      phone,
      image,
      created_at,
      updated_at
    )
    VALUES (?,?,?,?,?,?,?,?,?,?)`;
  
    db.query(
      sql,
      [
        user.name,
        user.last_name,
        user.last_name2,
        user.email,
        hashWith2y,
        user.address,
        user.phone,
        user.image,
        new Date(),
        new Date(),
      ],
      (err, res) => {
        if (err) {
          console.error(err);
          result(err, null);
        } else {
          console.log("id: " + res.insertId);
          result(null, res.insertId);
        }
      }
    );
  };

module.exports = User;
