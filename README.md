# Dream House - House Rental Management System

Dream House is a web-based House Rental Management System designed to streamline the rental process between landlords and tenants. The system allows landlords to list available properties, manage rental details, and track tenant information, while tenants can browse listings, submit rental applications, and make payments online. This project focuses on improving the efficiency, transparency, and user experience in the house rental process.

## Screenshots
Dafault/Homepage Interface
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/GuestUser/Homepage.png)

Property Listing Page (Filter)
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/GuestUser/filter.png)


### Tenant
Tenant is a registered user whose rental contract has been approved by the admin based on the agent’s submission. Once approved, the user’s role automatically changes from Guest to Tenant, granting access to the Tenant dashboard. Tenants can have multiple rental contracts, which are organized and displayed in tabbed sections for easy viewing. They can also make online payments securely via the Stripe payment gateway.

Note: if the tenant fails to pay the required deposit within 7 days of contract approval, the contract will be automatically voided.

Tenant-Dashboard
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Tenant/dashboard.png)

Tenant-Contract
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Tenant/contract-1.png)


### Landlord
Landlord is a user who must register directly with the admin to gain access. Once registered and approved, landlords can log in to submit properties for rent by filling out a detailed form that includes property information, map link, and images. After submission, the property will be reviewed by the admin. Once approved and assigned to an agent, the property will appear on the public property listing page, visible to all guests.

Landlord-Property-View
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Landlord/property-view-1.png)

![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Landlord/property-view-2.png)

![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Landlord/property-view-3.png)


### Agent
Agent is responsible for handling rental appointments and managing tenant relationships. When a user wants to rent a house, they must first set an appointment with the assigned agent. The agent will attend the appointment and assist the user in deciding whether to proceed. If the user agrees to rent, the agent must submit a contract creation form and wait for admin approval. Once approved, the user is automatically upgraded to the Tenant role. Agents are also responsible for reminding tenants about monthly payments and responding to any complaints or issues reported by their tenants.

Agent-Dashboard
![Image Alt](https://github.com/aiymnn/House-Rental-Management-System-DreamHouse/blob/4b40f427c7b2a85c965e668280b5a016f7af10aa/screenshorts/Agent/dashboard.png)


### Staff/Admin
Admin has full authority to manage and control the entire system. This includes registering and managing agents and landlords, approving property listings, assigning agents to new properties, and approving rental contracts submitted by agents. Admins can also monitor agent performance through KPI tracking, such as the number of appointments handled and successful deals closed. Agents are considered part of the staff and are overseen by the admin for operational efficiency.

Admin-Dashboard


Admin-Agent-KPI
