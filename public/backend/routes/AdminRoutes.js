

const adminController = require('../controllers/adminControllers');

module.exports = function(app) {



  app.post('/intranet/public/backend/api/users/login/admin/', adminController.login);
};
