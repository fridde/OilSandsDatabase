<form action="create_button.php" method="post">
    <p>Button Name<input type="text" name="button_name"></p>
    First parameter<input type="text" name="par1"><br>
    Second parameter<input type="text" name="par2">
    <input type="submit" value="Create Button">
    <p><textarea name="button_content"></textarea></p>
</form>
<form action="create_button.php?update_only=true" method="post">
      <input type="submit" value="Only update buttons">    
</form>


