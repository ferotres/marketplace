# MARKETPLACE PROYECT


### Building 

- Build images

        make build
        
- Create containers

        make dev-deploy
        
- Create Database Schema
  
        make migrate   
          
- Load dump of database with your database manager

        sql/marketplace.sql

- Run tests

        make all-tests


### API endpoints exposed  

Api is running on http://127.0.0.1:8008/api as API_PATH

#### Product Endpoints

- List all products with pagination.
        
        GET {API_PATH}}/products
        
        parameters: 
            - offset (OPTIONAL)
            
- Create a Product.

        POST {API_PATH}}/product
            
        body JSON: 
          - name : name of product (REQUIRED)
          - reference : reference of product (REQUIRED)
          
- Update a Product.

        PUT {API_PATH}}/product/{productId}
            
        body JSON: 
          - name : name of product (REQUIRED)
          - reference : reference of product (REQUIRED)
          
- Delete a Product.

        DELETE {API_PATH}}/product/{productId}
          
#### Seller Endpoints
            
- Create a Seller.

        POST {API_PATH}}/seller
            
        body JSON: 
          - name : name of seller (REQUIRED)
          - email : email of seller (REQUIRED)
          
- Update a Seller.

        PUT {API_PATH}}/seller/{sellerId}
            
        body JSON: 
          - name : name of seller (REQUIRED)
          - email : email of seller (REQUIRED)
          
- Delete a Seller.

        DELETE {API_PATH}}/seller/{sellerId}  
        
#### Seller Products Endpoints
            
- Add product to a Seller inventory.

        POST {API_PATH}}/seller/{sellerId}/product/{productId}
            
        body JSON: 
          - stock : inventory of product (REQUIRED)
          - amount : amount of seller for this product (REQUIRED)
          
- Update a product of a seller inventory.

        PATCH {API_PATH}}/seller/product/{sellerProductId}
            
        body JSON: 
          - stock : inventory of product (REQUIRED)
          - amount : amount of seller for this product (REQUIRED)
          
- Remove a product of a Seller inventory.

        DELETE {API_PATH}}/seller/product/{sellerProductId}
        
#### Cart Endpoints

- Create A Cart .

        POST {API_PATH}}/cart
            
        body JSON: 
          - customerId : customer Id (REQUIRED)
          
         customer stored in database is {58952145-1a8f-11ea-b51d-0242ac140002}
          
- Remove A Cart.

        DELETE {API_PATH}}/cart/{cartId}
            
- Commit A Cart.

        PUT {API_PATH}}/cart/{cartId}/commit
        
        when cart is committed, add/remove products is not permitted
        
- Add product to Cart.

        POST {API_PATH}}/cart/{cartId}/product/{productId}
            
        body JSON: 
          - quantity : (integer) quantity of product (REQUIRED)

- Remove product of the Cart.

        DELETE {API_PATH}}/cart/{cartId}/item/{cartItemId}
                    