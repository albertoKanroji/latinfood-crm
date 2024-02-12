

const userController = require('../controllers/usersControllers');
const adminController = require('../controllers/adminControllers');

module.exports = function(app) {
  app.post('/intranet/public/backend/api/customers/create/', userController.register);
  app.post('/intranet/public/backend/api/customers/login/', userController.login);


  app.post('/intranet/public/backend/api/users/login/admin/', adminController.login);
};
