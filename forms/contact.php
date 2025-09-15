<?php
  /**
  * Contact Form Handler for Skill Sprout Technologies
  * Sends contact form submissions to Info@skillsprouttechnologies.com
  */

  // Set the receiving email address
  $receiving_email_address = 'Info@skillsprouttechnologies.com';
  
  // Get form data
  $name = isset($_POST['name']) ? trim($_POST['name']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
  $message = isset($_POST['message']) ? trim($_POST['message']) : '';
  
  // Validate required fields
  $errors = array();
  
  if (empty($name)) {
    $errors[] = 'Name is required';
  }
  
  if (empty($email)) {
    $errors[] = 'Email is required';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
  }
  
  if (empty($subject)) {
    $errors[] = 'Subject is required';
  }
  
  if (empty($message)) {
    $errors[] = 'Message is required';
  }
  
  // If there are validation errors, return them
  if (!empty($errors)) {
    echo json_encode(array('status' => 'error', 'message' => implode(', ', $errors)));
    exit;
  }
  
  // Prepare email content
  $email_subject = "New Contact Form Submission: " . $subject;
  $email_body = "
  <html>
  <head>
    <title>New Contact Form Submission</title>
    <style>
      body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
      .container { max-width: 600px; margin: 0 auto; padding: 20px; }
      .header { background-color: #ff4a17; color: white; padding: 20px; text-align: center; }
      .content { background-color: #f9f9f9; padding: 20px; }
      .field { margin-bottom: 15px; }
      .label { font-weight: bold; color: #ff4a17; }
      .value { margin-top: 5px; }
      .footer { background-color: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
  </head>
  <body>
    <div class='container'>
      <div class='header'>
        <h2>New Contact Form Submission</h2>
        <p>Skill Sprout Technologies Website</p>
      </div>
      
      <div class='content'>
        <div class='field'>
          <div class='label'>Name:</div>
          <div class='value'>" . htmlspecialchars($name) . "</div>
        </div>
        
        <div class='field'>
          <div class='label'>Email:</div>
          <div class='value'>" . htmlspecialchars($email) . "</div>
        </div>
        
        <div class='field'>
          <div class='label'>Subject:</div>
          <div class='value'>" . htmlspecialchars($subject) . "</div>
        </div>
        
        <div class='field'>
          <div class='label'>Message:</div>
          <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
        </div>
        
        <div class='field'>
          <div class='label'>Submission Time:</div>
          <div class='value'>" . date('Y-m-d H:i:s') . "</div>
        </div>
      </div>
      
      <div class='footer'>
        <p>This email was sent from the Skill Sprout Technologies contact form.</p>
        <p>Reply directly to this email to respond to the sender.</p>
      </div>
    </div>
  </body>
  </html>
  ";
  
  // Email headers
  $headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: ' . $name . ' <' . $email . '>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion()
  );
  
  // Send email
  $mail_sent = mail($receiving_email_address, $email_subject, $email_body, implode("\r\n", $headers));
  
  if ($mail_sent) {
    // Also send a confirmation email to the sender
    $confirmation_subject = "Thank you for contacting Skill Sprout Technologies";
    $confirmation_body = "
    <html>
    <head>
      <title>Thank you for your message</title>
      <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #ff4a17; color: white; padding: 20px; text-align: center; }
        .content { background-color: #f9f9f9; padding: 20px; }
        .footer { background-color: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
      </style>
    </head>
    <body>
      <div class='container'>
        <div class='header'>
          <h2>Thank You!</h2>
          <p>Skill Sprout Technologies</p>
        </div>
        
        <div class='content'>
          <p>Dear " . htmlspecialchars($name) . ",</p>
          
          <p>Thank you for contacting Skill Sprout Technologies. We have received your message and will get back to you within 24 hours.</p>
          
          <p><strong>Your Message Details:</strong></p>
          <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
          <p><strong>Message:</strong> " . nl2br(htmlspecialchars($message)) . "</p>
          
          <p>If you have any urgent inquiries, please feel free to call us at +91 8106112043.</p>
          
          <p>Best regards,<br>
          Skill Sprout Technologies Team</p>
        </div>
        
        <div class='footer'>
          <p>Skill Sprout Technologies - Your Trusted Staffing Partner</p>
          <p>Email: Info@skillsprouttechnologies.com | Phone: +91 8106112043</p>
        </div>
      </div>
    </body>
    </html>
    ";
    
    $confirmation_headers = array(
      'MIME-Version: 1.0',
      'Content-type: text/html; charset=UTF-8',
      'From: Skill Sprout Technologies <Info@skillsprouttechnologies.com>',
      'X-Mailer: PHP/' . phpversion()
    );
    
    mail($email, $confirmation_subject, $confirmation_body, implode("\r\n", $confirmation_headers));
    
    echo json_encode(array('status' => 'success', 'message' => 'Message sent successfully'));
  } else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to send message. Please try again.'));
  }
?>
