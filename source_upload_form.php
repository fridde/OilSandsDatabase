
<h1>Upload your data</h1>
<form action="source_upload.php" method="post" id="Data">
    <p> Source <input type="text" name="Source" size="45"> </p>
    <p>
        <?php
        echo "Institution";
        echo "<select name='Institution'>";
        echo '<option value="none" selected>Select...</option>';

        foreach (ORM::for_table('osdb_Sources')->distinct()->
                select('Institution')->find_result_set() as $Source) {
            echo "<option value='" . $Source->Institution . "'>" .
            $Source->Institution . "</option>";
        }
        echo "</select>";
        ?>
        <br>
        New institution <input type="text" name="NewInstitution" 
                               size="45"> 
    </p>
    <p> Source URL <input type="url" name="SourceUrl" size="65"> </p>
    <p>
        Month and year of publication <input type="month" name="PublicationDate">
    </p>

    <p>
        <input type="checkbox" name="Prognosis" 
               value="Prognosis"> 
        Contains prognosed data<br>
        <input type="checkbox" name="Reported" 
               value="Reported"> 
        Contains reported data
    </p>
    <p>Time accuracy in months 
        <input type="number" name="TimeAccuracy" value="0" 
               min="0" step="1" > <br>
        (Keep "0" if not applicable)
    </p>
    <p>
        Product 
        <?php
        echo "<select name='Product'>";
        echo '<option value="Bitumen" selected>Bitumen</option>';
        foreach (ORM::for_table('osdb_Sources')->distinct()->
                select('Product')->find_result_set() as $Source) {
            echo "<option value='" . $Source->Product . "'>" .
            $Source->Product . "</option>";
        }
        echo "</select>";
        ?>
        <br>
        New Product <input type="text" name="NewProduct" size="30">
    </p>

    <p>
        Unit 
        <?php
        echo "<select name='Unit'>";
        echo '<option value="Barrels per day" selected>Barrels per day</option>';
        foreach (ORM::for_table('osdb_Sources')->distinct()->
                select('Unit')->find_result_set() as $Source) {
            echo "<option value='" . $Source->Unit . "'>" .
            $Source->Unit . "</option>";
        }
        echo "</select>";
        ?>
        <br>
        New Unit <input type="text" name="NewUnit" size="30">
    </p>


    <p><input type="submit" ></p>



</form>

<h3>Data</h3> 
<textarea placeholder="Enter your data here..." name="RawData" form="Data">

</textarea> 


