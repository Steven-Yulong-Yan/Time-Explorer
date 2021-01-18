<?php
$pageTitle = "Maze Game";
include('header.php');


    // Set dimensions of maze
    $dimension = 14;
    $totalSquares = $dimension**2;

    // Setting up initial table with border
    $mazeStructureArray = array_fill(0, $totalSquares, [
        "N" => true,
        "E" => true,
        "S" => true,
        "W" => true
        ]);

    for ($i = 0; $i < $dimension; $i++){
        $mazeStructureArray[$i]["N"] = "boundary";
        $mazeStructureArray[$totalSquares - 1 - ($dimension * $i)]["E"] = "boundary";
        $mazeStructureArray[$totalSquares - $dimension - ($dimension * $i)]["W"] = "boundary"; 
        $mazeStructureArray[$totalSquares - 1 - $i]["S"] = "boundary";
    }

    // Generate maze
    $startPos = $totalSquares - 1;
    $currentPos = $startPos;
    $previousDirection = "";
    $count = 0;
    $cellsVisited = [$startPos];
    $cellsVisitedOrder = [$startPos];
    $booleanForNewDirection = true;

    // Loops through until all squares are connected
    while(count($cellsVisited) < $totalSquares){
        $directionToShift = "";

        // If previous direction exists, set the new directions as trues (borders) and count all the true (border) values for that current position.
        if($previousDirection != ""){
            $booleanForNewDirection = true;
            $directionsFree = count(array_filter($mazeStructureArray[$currentPos], "checkForTrue")) - 1; // minus 1 to remove the opposite of the previous direction
        } 
        
        // Else if the directions to count are false (no borders), check which directions contain a border that isn't a boundary and hasn't been visited before
        else if ($booleanForNewDirection == false){
            $directionsFree = 0;
            foreach($mazeStructureArray[$currentPos] as $direction => $available){
                if($direction == "N" && $available === false && $available !== "boundary" && !in_array($currentPos - $dimension, $cellsVisited)){$directionsFree = $directionsFree + 1;}
                if($direction == "E" && $available === false && $available !== "boundary" && !in_array($currentPos + 1, $cellsVisited)){$directionsFree = $directionsFree + 1;}
                if($direction == "S" && $available === false && $available !== "boundary" && !in_array($currentPos + $dimension, $cellsVisited)){$directionsFree = $directionsFree + 1;}
                if($direction == "W" && $available === false && $available !== "boundary" && !in_array($currentPos - 1, $cellsVisited)){$directionsFree = $directionsFree + 1;}
            }
        
        // Else if it is the first time, count all the true (border) values for that current position.
        } else {
            $directionsFree = count(array_filter($mazeStructureArray[$currentPos], "checkForTrue"));
        }

        // If there is directions free, choose a random direction
        if($directionsFree > 0){
            $randomDirection = rand(1, $directionsFree);
            $successCount = 0;

            // Loops through each direction available and check that it has not been visited before, not a boundary, and that it matches the true or false (depending on what is being checked)
            foreach($mazeStructureArray[$currentPos] as $direction => $available){
                $cellValid = false;
                if((
                    ($direction == "N" && !in_array($currentPos - $dimension, $cellsVisited)) ||
                    ($direction == "E" && !in_array($currentPos + 1, $cellsVisited)) ||
                    ($direction == "S" && !in_array($currentPos + $dimension, $cellsVisited)) ||
                    ($direction == "W" && !in_array($currentPos - 1, $cellsVisited))) &&
                    $available == $booleanForNewDirection && 
                    $available !== "boundary"
                    ){

                    // If it matches all condiations count as success. When reaching random number of successes travel in that direction
                    $successCount++;
                    if($successCount == $randomDirection){
                        $directionToShift = $direction;
                    }
                }     
            }

            // record previous location and make sure there is not border in the direction travelled
            $previousPosition = $currentPos;
            $mazeStructureArray[$previousPosition][$directionToShift] = true;

            // Depending on the direction travelled, locate the new position, and make sure there is no border in the direction that was travelled previously.
            if($directionToShift == "N"){
                $currentPos = $currentPos - $dimension;
                $mazeStructureArray[$currentPos]["S"] = true;
            }
            if($directionToShift == "E"){
                $currentPos = $currentPos + 1;
                $mazeStructureArray[$currentPos]["W"] = true;
            }
            if($directionToShift == "S"){
                $currentPos = $currentPos + $dimension;
                $mazeStructureArray[$currentPos]["N"] = true;
            }
            if($directionToShift == "W"){
                $currentPos = $currentPos - 1;
                $mazeStructureArray[$currentPos]["E"] = true;
            }
            
            // Push the current position into both the cells visited total and the order
            array_push($cellsVisited, $currentPos);
            array_push($cellsVisitedOrder, $currentPos);
            $previousDirection = $directionToShift;


            // If the directions of the previous location are not boundaries and have not been visited, put up a border on that direction and the direction from that corresponding cell.
            if(!in_array($previousPosition + 1, $cellsVisited) && $mazeStructureArray[$previousPosition]["E"] !== false && $mazeStructureArray[$previousPosition]["E"] !== "boundary"){
                $mazeStructureArray[$previousPosition]["E"] = false;
                $mazeStructureArray[$previousPosition + 1]["W"] = false;
            } 
            if(!in_array($previousPosition - 1, $cellsVisited) && $mazeStructureArray[$previousPosition]["W"] !== false && $mazeStructureArray[$previousPosition]["W"] !== "boundary"){
                $mazeStructureArray[$previousPosition]["W"] = false;
                $mazeStructureArray[$previousPosition - 1]["E"] = false;
            } 
            if(!in_array($previousPosition - $dimension, $cellsVisited) && $mazeStructureArray[$previousPosition]["N"] !== false && $mazeStructureArray[$previousPosition]["N"] !== "boundary"){
                $mazeStructureArray[$previousPosition]["N"] = false;
                $mazeStructureArray[$previousPosition - $dimension]["S"] = false;
            } 
            if(!in_array($previousPosition + $dimension, $cellsVisited) && $mazeStructureArray[$previousPosition]["S"] !== false && $mazeStructureArray[$previousPosition]["S"] !== "boundary"){
                $mazeStructureArray[$previousPosition]["S"] = false;
                $mazeStructureArray[$previousPosition + $dimension]["N"] = false;
            } 
        }

        // If no directions found, witch current position to the cell previous and set the search for false (borders)
        else {
            array_pop($cellsVisitedOrder);
            $currentPos = end($cellsVisitedOrder);
            $previousDirection = "";
            $booleanForNewDirection = false;
            
        }
    }

    // Displaying the table in HTML
    $htmlTable = "";
    $htmlTable = $htmlTable . "<table id='mazeTable'>";
    for ($i = 0; $i < $dimension; $i++){
        $htmlTable = $htmlTable . "<tr>";
        for ($j = 0; $j < $dimension; $j++){
            $arrayNumber = $i *  $dimension + $j;

            // Add a class to the cell if the direction to move is available
            $directionsFree = [];
            if($mazeStructureArray[$arrayNumber]["N"] === true){array_push($directionsFree, "north");}
            if($mazeStructureArray[$arrayNumber]["E"] === true){array_push($directionsFree, "east");}
            if($mazeStructureArray[$arrayNumber]["S"] === true){array_push($directionsFree, "south");}
            if($mazeStructureArray[$arrayNumber]["W"] === true){array_push($directionsFree, "west");}

            $directionClasses = implode(" ", $directionsFree);
            $htmlTable = $htmlTable . "<td class='". $directionClasses ."'></td>";
        }
        $htmlTable = $htmlTable . "</tr>";
    }
    $htmlTable = $htmlTable . "</table>";
    // Check the variable to see whether it is true
    function checkForTrue($var){
        if($var === true){
            return true;
        }
    }
?>

<h1>Maze mini-game</h1>
<p class="instructions">Navigate your way through the backstage maze to find the musicians sheet music. Use your <strong>left, right, down, and up keys</strong> to move the arrow to the white sheet of paper at the end of the maze.</p>
<div id="minigame-maze">
    <?php echo $htmlTable; ?>
</div>
<p class="minigameFeedback correct"></p>
<div class="navigation-rowCentered">
    <button class="button" id="returnTo1914">Back</a>
    <button class="button" id="newMaze">New maze</a>
</div>

<?php include('footer.php'); ?>