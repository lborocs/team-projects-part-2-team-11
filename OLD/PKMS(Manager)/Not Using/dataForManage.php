<?php
                    // Sample data (replace with your actual data retrieval logic)
                    $boxesData = [
                        ['id' => 1, 'title' => 'Project 101'],
                        ['id' => 2, 'title' => 'Project 102'],
                        ['id' => 3, 'title' => 'Project 103'],
                    ];

                    // Generate HTML for clickable boxes and details divs
                    foreach ($boxesData as $box) {
                        echo '<div class="clickable-box" onclick="handleBoxClick(' . $box['id'] . ')">' . $box['title'] . '</div>';
                        echo '<div class="project-details" id="projectDetails' . $box['id'] . '" style="display: none;">';
                        echo '<button onclick="goBack(' . $box['id'] . ')">Back</button>';
                        echo '<h3>Project Details for ' . $box['title'] . '</h3>';
                        // Add your project details content here
                        echo '</div>';
                    }
                    ?>