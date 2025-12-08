flowchart LR
 subgraph C_Zone["Customer Features"]
    direction TB
        Browse["Browse Products"]
        Search["Search & Filter"]
        Cart["Manage Cart"]
        Checkout["Checkout & Pay"]
        History["Order History"]
        Recs["Recommendations"]
        Account["Account Mgmt"]
  end
 subgraph A_Zone["Admin Features"]
    direction TB
        Dash["Dashboard & Stats"]
        Prod_Mgmt["Product Mgmt"]
        Order_Mgmt["Order Mgmt"]
        Cust_Mgmt["Customer Mgmt"]
        Inv_Mgmt["Inventory Mgmt"]
  end
 subgraph System["d4ily System: Vintage Thrift Shop"]
    direction TB
        C_Zone
        A_Zone
  end
    Customer(("Customer")) --> Browse & Search & Cart & Checkout & History & Recs & Account
    Admin(("Admin")) --> Dash & Prod_Mgmt & Order_Mgmt & Cust_Mgmt & Inv_Mgmt

     Browse:::nodeStyle
     Search:::nodeStyle
     Cart:::nodeStyle
     Checkout:::nodeStyle
     History:::nodeStyle
     Recs:::nodeStyle
     Account:::nodeStyle
     Dash:::nodeStyle
     Prod_Mgmt:::nodeStyle
     Order_Mgmt:::nodeStyle
     Cust_Mgmt:::nodeStyle
     Inv_Mgmt:::nodeStyle
     Customer:::actorStyle
     Admin:::actorStyle
    classDef actorStyle fill:#f9f9f9,stroke:#333,stroke-width:2px
    classDef systemStyle fill:#fff,stroke:#333,stroke-dasharray: 5 5
    classDef zoneStyle fill:#f4f4f4,stroke:none
    classDef nodeStyle fill:#fff,stroke:#333,stroke-width:1px