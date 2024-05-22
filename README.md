# IT for Hire - Job Portal 🚀

Welcome to IT for Hire, a job portal application designed to connect job seekers with potential employers in the IT industry. This project aims to streamline the job search process for both candidates and recruiters, offering a platform where job postings can be easily managed and applications efficiently processed.

## Features ✨

- **User Authentication**: Secure login system for both job seekers and employers.
- **User Management**: Admins can view and delete user accounts.
- **Job Listings**: Browse and search through a comprehensive list of IT job openings.
- **Apply for Jobs**: Job seekers can apply for positions directly through the platform.
- **Post Jobs**: Employers can post job listings, complete with descriptions and requirements.
- **Category Management**: Admins can manage job categories to keep listings organized.
- **Skill Management**: Admins can add or remove skills required for job listings.
- **Message Management**: Admins can view and delete contact messages sent through the portal.

## Setting Up the Project 💻

### Prerequisites

- PHP
- MySQL
- XAMPP (or similar local server environment)

### Installation Steps

1. Clone this repository to your local machine.
2. Import the database structure from `db/job_portal.sql`.
3. Configure XAMPP or your local server environment.
4. Start the server and navigate to `localhost` in your web browser.
5. You're all set! You can now explore the features of IT for Hire.

## Database Schema 🗃️

The database schema consists of the following tables:

- `users`: Stores user information for authentication.
- `admin`: Stores admin accounts for managing the system.
- `categories`: Stores job categories for organizing listings.
- `companies`: Stores information about companies posting job listings.
- `skills`: Manages IT skills required for job listings.
- `userSkills`: Tracks the skills of individual users.
- `jobs`: Contains job listings posted by companies.
- `applicants`: Tracks job applications submitted by users.
- `contact_messages`: Stores messages sent through the contact form.

## User Login 🚪

For access to the admin dashboard:
- Username: admin
- Password: admin

Regular users will be directed to a special page to log in with their admin credentials.

## Contributing 🤝

Contributions are welcome! If you have any ideas for improvements or would like to report a bug, please submit an issue or a pull request.

## License 📄

This project is owned by Gyuhwan Choi. You have the full rights to the project as its creator.

## Created By 👨‍💻

This project was created by Gyuhwan Choi.