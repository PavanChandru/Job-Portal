<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT For Hire</title>
    <link rel="stylesheet" href="css/footer.css">
    <style>
        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-content">
            <div class="footer-section about">
                <h2 class="logo">IT FOR HIRE</h2>
                <p>
                    We connect job seekers with employers in the IT industry.
                    Whether you're looking for your next gig or the perfect hire,
                    we're here to help.
                </p>
                <div class="contact">
                    <span><i class="fas fa-phone"></i>(312) 567-3000</span>
                    <span><i class="fas fa-envelope"></i>info@itforhire.com</span>
                </div>
                <div class="socials">
                    <a href="#"><img src="media/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="media/twitter.png" alt="Twitter"></a>
                    <a href="#"><img src="media/instagram.png" alt="Instagram"></a>
                    <a href="#"><img src="media/linkedin.png" alt="LinkedIn"></a>
                </div>
            </div>
            <div class="footer-section links">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="service.php">Service</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="search.php">Search Jobs</a></li>
                </ul>
            </div>
            <div class="footer-section contact-form">
                <h2>Contact Us</h2>
                <form action="process_contact.php" method="post" id="contact-form">
                    <input type="text" name="name" class="text-input contact-input" placeholder="Your name">
                    <input type="email" name="email" class="text-input contact-input" placeholder="Your email address">
                    <textarea name="message" class="text-input contact-input" placeholder="Your message"></textarea>
                    <button type="submit" class="btn btn-big contact-btn" name="submit_form">
                        <i class="fas fa-envelope"></i>
                        Send
                    </button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 IT FOR HIRE. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Function to display success message and clear form fields
        function displaySuccessMessage() {
            alert("Your message was successfully sent!");
            document.getElementById("contact-form").reset(); // Reset the form fields
        }

        // Check if form was submitted successfully and display success message
        document.getElementById("contact-form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            
            // AJAX code to submit the form data
            fetch('process_contact.php', {
                method: 'POST',
                body: new FormData(document.getElementById('contact-form'))
            })
            .then(response => response.text())
            .then(data => {
                displaySuccessMessage(); // Call function to display success message
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>


<style>
    /* Footer CSS, created by Gyuhwan Choi */
    .footer-section.links h2 {
        color: #baf8ba;
    }
    .footer-section.contact-form h2 {
        color: #fcd48f;
    }
    footer {
        background-color: #111;
        color: #fff;
        font-family: Arial, sans-serif;
        padding: 80px 0;
    }

    .footer-content {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .footer-content h2 {
        font-size: 2.5rem;
    }

    .footer-section {
        flex-basis: 30%;
        margin-bottom: 50px;
    }

    .about h2.logo {
        color: #aaa;
    }

    .footer-content p {
        line-height: 1.8;
        margin-bottom: 20px;
        color: #E5E5CC;
    }

    .contact span {
        display: block;
        margin-bottom: 10px;
    }

    .socials a {
        display: inline-block;
        width: 40px;
        height: 40px;
        margin-right: 10px;
        border-radius: 50%;
        color: #fff;
        background-color: #333;
        text-align: center;
        line-height: 40px;
    }

    .socials img {
        width: 40px;
    }

    .links ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .links li {
        margin-bottom: 10px;
    }

    .links a {
        color: #fff;
        text-decoration: none;
        font-size: 1.2rem;
    }

    .contact-form input[type="email"],
    .contact-form textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
    }

    .contact-form textarea {
        height: 120px;
    }

    .contact-form button[type="submit"] {
        display: block;
        margin-top: 20px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #333;
        color: #fff;
        cursor: pointer;
    }

    .contact-form button[type="submit"]:hover {
        background-color: #555;
    }

    .footer-bottom {
        background-color: #000;
        padding: 10px 0;
        text-align: center;
        font-size: 0.9rem;
    }

    .footer-bottom p {
        margin: 0;
    }

    @media (max-width: 1200px) {
        footer {
            background-color: #111;
            color: #fff;
            font-family: Arial, sans-serif;
            padding: 40px 0;
        }

        .footer-content {
            max-width: 90%;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-section {
            flex-basis: 50%;
            margin-bottom: 30px;
            text-align: center;
        }

        .about h2.logo {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .about p {
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .contact span {
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .socials a {
            display: inline-block;
            width: 30px;
            height: 30px;
            margin-right: 5px;
            border-radius: 50%;
            color: #fff;
            background-color: #333;
            text-align: center;
            line-height: 30px;
            font-size: 1rem;
        }

        .socials img {
            width: 30px;
        }

        .links ul {
            list-style: none;
            margin: 0;
            padding: 0;
            font-size: 1rem;
        }

        .links li {
            margin-bottom: 10px;
        }

        .links a {
            color: #fff;
            text-decoration: none;
        }

        .contact-form input[type="email"],
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }

        .contact-form textarea {
            height: 120px;
        }

        .contact-form button[type="submit"] {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 1rem;
        }

        .contact-form button[type="submit"]:hover {
            background-color: #555;
        }

        .footer-bottom {
            background-color: #000;
            padding: 10px 0;
            text-align: center;
            font-size: 0.8rem;
        }

        .footer-bottom p {
            margin: 0;
        }
    }
</style>