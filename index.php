<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EQ Test - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            text-align: center;
            margin: 20px;
        }
        h1 {
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-size: 1.2em;
            cursor: pointer;
            margin: 10px;
            transition: background 0.3s, transform 0.2s;
        }
        .btn:hover {
            background: #2980b9;
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 2em;
            }
            .btn {
                padding: 10px 20px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Emotional Intelligence Test</h1>
        <p>
            Discover your Emotional Intelligence (EQ) with our interactive test. Emotional Intelligence is the ability to understand and manage your own emotions, as well as recognize and influence the emotions of others. Take this test to assess your self-awareness, empathy, and emotional regulation skills.
        </p>
        <button class="btn" onclick="window.location.href='quiz.php?q=1'">Start Test</button>
        <button class="btn" onclick="window.location.href='signup.php'">Sign Up</button>
        <button class="btn" onclick="window.location.href='login.php'">Login</button>
    </div>
</body>
</html>
