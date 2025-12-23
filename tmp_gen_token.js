const jwt = require('jsonwebtoken');
const secret = 'Zk7qT2pL9vB3xM6sG1yR4wN8hC5dK0jF2uV7aP3rX9mL4tQ';
const payload = {
  sub: '1',
  email: 'admin@dvi.co.in',
  role: 1,
  agentId: 0,
  staffId: 0,
  guideId: 0,
};
const token = jwt.sign(payload, secret, { expiresIn: '7d' });
console.log(token);
