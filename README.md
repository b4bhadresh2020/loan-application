
## Loan API Project

### Installation
This project can be easily started using [Laravel passport]

\
**Setup Steps**:
- clone git repo to local
- `cd` to downloaded folder
- composer install
- php artisan key:generate
- run db migrations with seed `php artisan migrate:fresh --seed`
- php artisan serve
- use postman collection(from repo or [this link](https://www.getpostman.com/collections/87d10a43cde1f5c136fc)) to start using APIs


**Unit Test**:
- php artisan test

**Unit Test Code Coverage Report**:
https://prnt.sc/26xpt4d

#### API Endpoints
\
**Public APIs** (without authentication):
 - POST - /api/v1/register (Register API)
 - POST - /api/v1/login (Login API)
 
 \
 **Private APIs** (with authentication header bearer token):
  - GET -  /api/v1/profile (Logout) 


  - GET  - /api/v1/loan-applications (Get all loan applications of current user)
  - POST - /api/v1/loan-applications (Apply for a new Loan)
  
  
  - GET  - /api/v1/repayments (Get all repayments schedule with loan detail)
  - POST - /api/v1/repayments/{loanRepayment} (Repay pending Repayment/EMI)
  
 \
 **Admin Private APIs** (with authentication header bearer token):
   - GET -   /api/v1/admin/loan-applications/{{loanApplication}} (Get a loan detail) 
   - POST -  /api/v1/admin/verify/loan-applications/{{loanApplication}} (Approve/Reject a loan) 
 