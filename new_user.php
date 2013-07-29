<table class="GrayBlack" align="center">
                                <tbody id="data">
                                    <tr>
                                        <td>S.no</td>
                                        <td>Username</td>
                                        <td>Email</td>
                                        <td>Name</td>
                                        <td>Role</td>
                                        <td>Edit</td>
                                    </tr>


                                    <?php
                                    $query1 = "SELECT * from admin_users";
                                    $result = mysql_query($query1);
                                    while ($row = mysql_fetch_array($result)) {
                                        ?>

                                        <tr>
                                            <td ><?php echo $row['user_id']; ?></td>
                                            <td ><?php echo $row['username']; ?></td>
                                            <td ><?php echo $row['email']; ?></td>
                                            <td ><?php echo $row['name']; ?></td>
                                            <td ><?php echo $row['role']; ?></td>
                                            <td ><a href="user_edit.php?user_id=<?php echo($row['user_id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a>
                                                <a href="user_delete.php?user_id=<?php echo($row['user_id']) ?>" title="Delete" onclick ="return confirm('are you sure you want to delete');" > <img src="images/icon_delete.png" /> </a> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>