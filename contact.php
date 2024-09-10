<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/contact.css" />
  </head>
  <body>
    <?php require "./component/header.php" ?>
    <section class="contact">
      <div class="cont-image">
        <img src="./images/contact.png" alt="Contact Image" />
      </div>
      <div class="contact-form">
        <h1>Contact Us</h1>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi ut ad
          magnam.
        </p>
        <form>
          <input
            type="text"
            name="name"
            placeholder="Your Full Name"
            required
          />
          <input
            type="email"
            name="email"
            placeholder="Please Enter Your Email"
            required
          />
          <input type="text" name="subject" placeholder="Write a Subject" />
          <textarea
            name="mssg"
            cols="30"
            rows="10"
            placeholder="Write a Message"
          ></textarea>

          <button class="btn" type="submit">Submit</button>
        </form>
      </div>
    </section>
    <?php require "./component/footer.php" ?>
  </body>
</html>