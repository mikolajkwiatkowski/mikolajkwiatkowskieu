<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwiatkowski Mikołaj</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
        #tabelka {
            display: flex; 
            justify-content: center;
            align-items: center;
            
            width: 100%;
            height: 45rem;
        }
        #autor
        {
            font-size: 45px;
            font-weight: bold;
            
        }
        #footer{
            display: flex; 
            justify-content: center;
            align-items: center;
            
            width:100%;
            height: 10rem;
        }
        table {
            border-collapse: collapse; 
            width: 25%;
            height: 30rem;
        }
        
        th, td {
            border: 1px solid black; 
            text-align: center;
            
        }
        td{
            padding: 1rem;
            
        }
        
        th {
            padding: 1rem; 
            line-height: 1; 
            background-color: #1c6136;
            color: white;
        }
        
        #nrzad {
            font-size: 30px;
            text-transform: uppercase;
            font-weight: bold;
            
        }
        a{
            font-size: 40px; 
            font-weight: bold;
            text-decoration: none; 
        }
        
    </style>
    <div id="tabelka">
        <table>
            <tr>
                <th>
                    <span id="nrzad">podpunkty</span>
                    
                </th>
            </tr>
            <tr><td><a href="/Zadanie1/index1.php">Podpunkt 1</a></td></tr>
            <tr><td><a href="/Zadanie1/index2.php">Podpunkt 2</a></td></tr>
            <tr><td><a href="/Zadanie1/index3.php">Podpunkt 3</a></td></tr>
            
        </table>
    </div>
    <div id="footer"><p id="autor">Mikołaj Kwiatkowski gr1 nr 120650</p></div>


</body>
</html>
