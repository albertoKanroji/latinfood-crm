const db = require("../config/config");
const bcrypt = require("bcrypt-nodejs");
const User = {};

User.findById = (id, result) => {
    const sql = `SELECT 
    name,
email,
password,
phone,
image
WHERE id =?
`;
    db.query(sql, [id], (err, user) => {
        if (err) {
            console.error(err);
            result(err, null);
        } else {
            console.log("admin: " + user);
            result(null, user);
        }
    });
};
User.findByEmail = (email, result) => {
    const sql = `
      SELECT
      id,
        name,
        email,
        password,
        phone,
        image
      FROM
        users
      WHERE
        email = ?
    `;

    db.query(sql, [email], (err, user) => {
        if (err) {
            console.log("Error:", err);
            result(err, null);
        } else {
            try {
              //  console.log("ADMIN", user[0]);
                result(null, user[0]);
            } catch (error) {
                console.log("Error al obtener el usuario:", error);
                result(error, null);
            }
        }
    });
};

module.exports = User;
