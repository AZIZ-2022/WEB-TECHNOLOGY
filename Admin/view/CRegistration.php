<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
</head>
<body>
    <form action="../control/CustomerReg_control.php" method="post" enctype="multipart/form-data">
        <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td align="center">
                    <h1>Customer Registration</h1>
                    <table width="60%" cellspacing="10" cellpadding="10" border="1">
                       
                        <tr>
                            <td colspan="2" align="center"><h3>Personal Details</h3></td>
                        </tr>
                        <tr>
                            <td><b>Full Name:</b></td>
                            <td><input type="text" name="name" placeholder="Enter your full name" ></td>
                        </tr>
                        <tr>
                            <td><b>Email:</b></td>
                            <td><input type="text" name="email" placeholder="Enter your email" ></td>
                        </tr>
                        <tr>
                            <td><b>Password:</b></td>
                            <td><input type="password" name="password" placeholder="Create a password" ></td>
                        </tr>
                        <tr>
                            <td><b>Confirm Password:</b></td>
                            <td><input type="password" name="confirm_password" placeholder="Confirm your password" ></td>
                        </tr>
                        <tr>
                            <td><b>Phone:</b></td>
                            <td><input type="text" name="phone" placeholder="Enter your phone number" ></td>
                        </tr>
                        <tr>
                            <td><b>Address:</b></td>
                            <td><textarea name="address" placeholder="Enter your address" rows="4" ></textarea></td>
                        </tr>
                        <tr>
                            <td><b>Profile Picture:</b></td>
                            <td><input type="file" name="profile_picture" accept="image/*" ></td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" value="Register">
                                <input type="reset" value="Clear">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
