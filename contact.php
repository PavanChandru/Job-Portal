<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Job Portal</title>
    <style>
        /* Add CSS for the submit button */
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 1rem;
            margin-bottom: 20px; /* Add margin below the button */
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Style for contact members section */
        .contact-members {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .contact-member {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .contact-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .contact-member-info {
            flex: 1;
        }

        .contact-member-info h3 {
            margin: 0 0 10px;
        }

        .contact-member-info p {
            margin: 0;
        }

        /* Style for contact section */
        .contact-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .contact-section h2 {
            margin-top: 0;
        }

        .contact-section p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <section class="contact-members">
            <h2>Contact Members</h2>
            <div class="contact-member">
                <img src="media/post.png" alt="Placeholder Image">
                <div class="contact-member-info">
                    <h3>Gyuhwan Choi</h3>
                    <p>Email: gchoi6@hawk.iit.edu</p>
                    <p>Phone: +1 312-1283-1942</p>
                </div>
            </div>
            <div class="contact-member">
                <img src="media/post.png" alt="Placeholder Image">
                <div class="contact-member-info">
                    <h3>John Doe</h3>
                    <p>Email: john.doe@example.com</p>
                    <p>Phone: +1 234 567 890</p>
                </div>
            </div>
            <div class="contact-member">
                <img src="media/post.png" alt="Placeholder Image">
                <div class="contact-member-info">
                    <h3>Jane Smith</h3>
                    <p>Email: jane.smith@example.com</p>
                    <p>Phone: +1 345 678 901</p>
                </div>
            </div>
        </section>

        <section class="contact-section">
            <h2>Need Help? Contact Us!</h2>
            <p>We're here to assist you. Whether you have questions, feedback, or need support, feel free to reach out to us using the form below.</p>
            <form action="process_contact.php" method="POST" id="contact-form">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
                <button type="submit" name="submit">Send</button>
            </form>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>
