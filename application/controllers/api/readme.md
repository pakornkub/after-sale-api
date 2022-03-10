## Structure API

step code structure API

1. form_validation ( case : receive input parameter)
2. Get Data from Database 
3. generateToken (case : first login)
4. validateToken (case : every call API for check status login and check permission use menu)

code check token

- print_r($this->authorization_token->userData());
- print_r($this->authorization_token->validateToken());

code CORS

- header('Access-Control-Allow-Origin: *'); 
- header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS'); 
- header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');



  