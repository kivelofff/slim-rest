Simple implementation of REST API customers for testing by Postman

tutorial: https://www.twilio.com/blog/create-restful-api-slim4-php-mysql

GET / - just info
GET /all - get all from customers
POST /add - add customer
PUT /update/{id} - edit customer by id
DELETE /delete/{id} - delete customer by id

customer data should be in JSON format.

example:

{
"name" : "amy",
"email" : "amy@mail.com",
"phone" : "123449988383"
}