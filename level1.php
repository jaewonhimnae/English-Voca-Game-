<?php
include("header.php");
$url = 'level1.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$level1 = json_decode($data); // decode the JSON feed
//shuffle($level1);
?>
<div class="quizPart">
    <div class="progressBar">
        <div class="progressBarBg">
            <div class="progress"></div>
        </div>
    </div>
    <span>Level 1</span>
    <p class="progressNumber" style="text-align: right; margin-bottom: -20px; margin-top: -22px;"></p>
    <br />

    <p style="margin-bottom: -3px; color: grey;">infinite</p>
    <div style="margin-bottom: 5px;">
        <span id="mainVoca"></span>
        <img class="rightSign rightWrongSign" src="icons/right.png" alt="right">
        <img class="wrongSign rightWrongSign" src="icons/wrong.png" alt="wrong">
    </div>
    <div class="row" style="margin-left:auto;">
        <input type="text" id="answerInput" class="form-control col-10 " id="answerInput" aria-describedby="answerInput" placeholder="">
        <button id="answerBtn" class="answerBtn " tabindex="-1" role="button" aria-disabled="true">Submit</button>
    </div>

    <h2 class="timer" style="display:none"></h2>
    <br>
    <div class="row" style="margin-left:auto;">
        <button id="fadesIn1" class="buttonTime fadesIn">&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;</button>
        <button id="fadesIn2" class="buttonTime fadesIn">&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;</button>
        <button id="fadesIn3" class="buttonTime fadesIn">&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;</button>
        <button id="fadesIn4" class="buttonTime fadesIn">&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;</button>
        <button id="fadesIn5" class="buttonTime fadesIn">&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;&nbsp;</button>
    </div>
    <br>

    <div class="button_cont" align="center">
        <button style="display:none;vertical-align: bottom;" id="checkIncorrectBtn" href="#" class="example_c checkIncorrectBtn" tabindex="-1" role="button" aria-disabled="true">
            Check Incorrect Answers
        </button>

        <button style="display:none" id="goTolevelTwoBtn" href="./level2.php" class="example_c goTolevelTwoBtn" tabindex="-1" role="button" aria-disabled="true">
            Go to Level 2
        </button>
    </div>

    <div id="wrongAnswer" style="display:none;border: 1px solid grey;border-radius: 8px;margin-top: 10px; padding:20px;">
        <del class="wrongAnswerTyped" style="font-size:2rem"></del>
        <h2> Wrong! Correct answer:</h2>
        <div style="margin-bottom:7px;">
            <span style="font-size:1.5rem;">뜻 :</span> <span class="meaning" style="font-size:1.5rem;"></span>
        </div>
        <li style="display:block">
            <div>
                <a id="playPronunciation" href="#" style="fontSize:30px">
                    <img class="playIcon" src="icons/play.png" alt="Play">
                    <span id="answerSound" class="answerSoundSpan" data-value=""></span>
                </a>
            </div>
            <div>
                <a id="playPronunciationTwo" href="#" style="fontSize:30px">
                    <img class="playIcon" src="icons/play.png" alt="Play">
                    <span id="answerSoundTwo" class="answerSoundSpan" data-value=""></span>
                </a>
            </div>
            <div>
                <a id="playPronunciationThree" href="#" style="fontSize:30px">
                    <img class="playIcon" src="icons/play.png" alt="Play">
                    <span id="answerSoundThree" class="answerSoundSpan" data-value=""></span>
                </a>
            </div>
        </li>
        <audio id="audio" controls="controls" style="display:none">
            <source id="audioSource">
            </source>
            Your browser does not support the audio format.
        </audio>
    </div>
</div>

<div class="resultPart" style="display:none; text-align:center;">
    <h1>Incorrect Answers</h1>
    <div class="incorrectVoca" style="font-size:1.23rem"></div>

    <br />
    <a id="goBackToQuizBtn" style="height:30px" href="#" class="example_c goBackToQuizBtn " tabindex="-1" role="button" aria-disabled="true">
        Go Back
    </a>
</div>

<script>
    //set all the variables here 
    const mainVoca = document.querySelector('#mainVoca'),
        answerInput = document.querySelector('#answerInput'),
        answerBtn = document.querySelector('#answerBtn'),
        progressNumber = document.querySelector('.progressNumber'),
        wrongAnswer = document.querySelector('#wrongAnswer'),
        playPronunciation = document.querySelector('#playPronunciation'),
        answerSound = document.querySelector('#answerSound'),
        playPronunciationTwo = document.querySelector('#playPronunciationTwo'),
        answerSoundTwo = document.querySelector('#answerSoundTwo'),
        playPronunciationThree = document.querySelector('#playPronunciationThree'),
        answerSoundThree = document.querySelector('#answerSoundThree'),
        progress = document.querySelector('.progress'),
        wrongAnswerTyped = document.querySelector('.wrongAnswerTyped'),
        timer = document.querySelector('.timer'),
        fadesIn1 = document.querySelector('#fadesIn1'),
        fadesIn2 = document.querySelector('#fadesIn2'),
        fadesIn3 = document.querySelector('#fadesIn3'),
        fadesIn4 = document.querySelector('#fadesIn4'),
        fadesIn5 = document.querySelector('#fadesIn5'),
        fadesIn = document.querySelectorAll('.fadesIn'),
        incorrectVoca = document.querySelector('.incorrectVoca'),
        quizPart = document.querySelector('.quizPart'),
        resultPart = document.querySelector('.resultPart'),
        checkIncorrectBtn = document.querySelector('.checkIncorrectBtn'),
        goTolevelTwoBtn = document.querySelector('.goTolevelTwoBtn'),
        goBackToQuizBtn = document.querySelector('.goBackToQuizBtn'),
        rightSign = document.querySelector('.rightSign'),
        wrongSign = document.querySelector('.wrongSign'),
        meaning = document.querySelector('.meaning');


    let incorrectVocas = []
    //To randomize the tense
    var myArray = ['simple', 'past']
    var randomTense = myArray[Math.floor(Math.random() * myArray.length)];

    let i = 0;

    //change level1 PHP data into javascript data
    var level1 = '<?php echo json_encode($level1); ?>'
    var level1Array = JSON.parse(level1);
    //when clicking   checkIncorrectBtn change page into Result Part
    checkIncorrectBtn.addEventListener('click', goToResultPart)

    function goToResultPart() {
        resultPart.style.display = "block";
        quizPart.style.display = "none";
    }

    //when clicking   goBackToQuizBtn change page into for Quiz Part
    goBackToQuizBtn.addEventListener('click', goToQuizPart)

    function goToQuizPart() {
        resultPart.style.display = "none";
        quizPart.style.display = "block";
    }

    //count down start and repeat when clicking the answerInput
    countDown();
    var counter = 10;

    function countDown() {
        //when repeating, needs to set the counter to 10 again.
        counter = 10;
        //when repeating, Can't click the answerInput again.
        var switch_on = false
        //start CountDown every 1 second triggered.
        var Interval = setInterval(function() {
            //after last quiz, stop every intervals 
            if (i === level1Array.length) return false;


            answerBtn.disabled = false;
            answerBtn.style.backgroundColor = "#008CBA"

            $(".fadesIn").css('background-color', '#DDDDDD')


            //Displaying counter number in browser
            timer.textContent = counter
            counter--

            //when counter goes down, it will change button's color 
            if (counter < 8) {
                fadesIn1.style.backgroundColor = "red";
            }
            if (counter < 6) {
                fadesIn2.style.backgroundColor = "red";
            }
            if (counter < 4) {
                fadesIn3.style.backgroundColor = "red";
            }
            if (counter < 2) {
                fadesIn4.style.backgroundColor = "red";
            }
            if (counter === 0) {
                answerInput.setAttribute("placeholder", "Click Here to RESTART!!!")

                incorrectVocas = incorrectVocas.concat(level1Array[i].voca + '/' + level1Array[i].simple + '/' + level1Array[i].past + '<br> ')

                fadesIn5.style.backgroundColor = "red";
                answerBtn.disabled = true;
                answerBtn.style.backgroundColor = "#DDDDDD"

                clearInterval(Interval);
                $("#answerInput").click(function() {

                    if (randomTense === 'simple') {
                        answerInput.setAttribute("placeholder", "SIMPLE PAST")
                    } else {
                        answerInput.setAttribute("placeholder", "PAST PARTICIPLE")
                    }

                    wrongAnswer.style.display = 'none';
                    if (switch_on === true) return false
                    countDown();
                    switch_on = true
                });
            }
        }, 1000);
    }

    // when hit Enter in Input,it will be submiited 
    answerInput.addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            answerBtn.click();
        }
    })

    progressNumber.textContent = i + '/' + level1Array.length
    progress.style.width = i / level1Array.length * 100 + '%'
    mainVoca.textContent = level1Array[i].voca

    if (randomTense === 'simple') {
        answerInput.setAttribute("placeholder", "SIMPLE PAST")
    } else {
        answerInput.setAttribute("placeholder", "PAST PARTICIPLE")
    }

    $(document).ready(function() {

        $("#answerBtn").click(function() {
            if (!level1Array[i]) return false;

            if (answerInput.value == null || answerInput.value == "") return alert('write the value first');

            let guess = answerInput.value;

            if (guess === (randomTense === 'simple' ? level1Array[i].simple : level1Array[i].past)) {

                $.ajax({
                    url: "./ajaxRight.php",
                    type: "POST",
                    data: {
                        i: i
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        //after finishing all of the quizes, show the btn for next process, and make the submit btn unable.
                        if (data === level1Array.length) {
                            checkIncorrectBtn.style.display = "inline-block";
                            goTolevelTwoBtn.style.display = "inline-block";
                            answerBtn.disabled = true;
                            answerBtn.style.backgroundColor = "#DDDDDD"
                            $(".fadesIn").addClass("disabled");
                        }

                        level1Array[i].answered = true
                        console.log(level1Array);
                        rightSign.style.display = "block";

                        setTimeout(() => {
                            rightSign.style.display = "none";
                        }, 2000);

                        //after submitting the answer, make the counter back to 10, so it can countdown from the start again.
                        counter = 10;
                        //fetching the data(Number) from the ajax result and put that value into i. 
                        i = data;
                        //after answering correct one, remove the wrongAnswer part, and input value.
                        wrongAnswer.style.display = 'none';
                        answerInput.value = "";
                        //index starts from 0 but when the number of level data is 20, so there is no level1Array[20], it will cause an error 
                        if (!level1Array[i]) {
                            i = i - 1
                        }
                        mainVoca.textContent = level1Array[i].voca;
                        // revert index back to normal order.
                        i = data;
                        // For progrees bar 
                        progressNumber.textContent = i + '/' + level1Array.length;
                        progress.style.width = i / level1Array.length * 100 + '%'
                        //change the Tense for next quiz, and Assign Input place holder Depending on the changed Tense.
                        randomTense = myArray[Math.floor(Math.random() * myArray.length)];
                        if (randomTense === 'simple') {
                            answerInput.setAttribute("placeholder", "SIMPLE PAST")
                        } else {
                            answerInput.setAttribute("placeholder", "PAST PARTICIPLE")
                        }

                    }, // End of success
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            } else {
                $.ajax({
                    url: "./ajaxWrong.php",
                    type: "POST",
                    data: {
                        i: i
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data === level1Array.length) {
                            checkIncorrectBtn.style.display = "inline-block";
                            goTolevelTwoBtn.style.display = "inline-block";
                            answerBtn.disabled = true;
                            answerBtn.style.backgroundColor = "#DDDDDD"
                            $(".fadesIn").addClass("disabled");

                            // for (let index = 0; index < array.length; index++) {
                            //     const element = level1Array[index].answered;
                            //     if (element === false) {
                            //         i = 0;
                            //     }
                            // }


                        }
                        counter = 10;
                        i = data;
                        progress.style.width = i / level1Array.length * 100 + '%'
                        progressNumber.textContent = i + '/' + level1Array.length;
                        wrongAnswerTyped.textContent = answerInput.value;

                        wrongSign.style.display = "block";

                        setTimeout(() => {
                            wrongSign.style.display = "none";
                        }, 2000);

                        // Put all of incorrect words into incorrectVocas Array, and then display 
                        incorrectVocas = incorrectVocas.concat(level1Array[i - 1].voca + '/' + level1Array[i - 1].simple + '/' + level1Array[i - 1].past + '<br> ')
                        // but In case there is a word already included in incorrect word because of TimeOut, 
                        // we need to filter the duplicate one. 
                        var names = incorrectVocas;
                        var uniqueIncorrectVocas = [];
                        $.each(names, function(i, el) {
                            if ($.inArray(el, uniqueIncorrectVocas) === -1) uniqueIncorrectVocas.push(el);
                        });

                        if (!level1Array[i]) {
                            i = i - 1
                        }
                        // Display incorrectVocas 
                        incorrectVoca.innerHTML = uniqueIncorrectVocas.join("\n");
                        answerInput.value = "";
                        mainVoca.textContent = level1Array[i].voca;
                        wrongAnswer.style.display = 'block';
                        i = data;

                        meaning.textContent = level1Array[i - 1].meaning;
                        // For Pronunciation part
                        answerSound.textContent = '현재형' + level1Array[i - 1].voca;
                        answerSound.dataset.dataValue = level1Array[i - 1].mp3;

                        answerSoundTwo.textContent = '과거형' + level1Array[i - 1].simple;
                        answerSoundTwo.dataset.dataValue = level1Array[i - 1].mp3;

                        answerSoundThree.textContent = '과거 분사형' + level1Array[i - 1].past;
                        answerSoundThree.dataset.dataValue = level1Array[i - 1].mp3;

                        randomTense = myArray[Math.floor(Math.random() * myArray.length)];
                        if (randomTense === 'simple') {
                            answerInput.setAttribute("placeholder", "SIMPLE PAST")
                        } else {
                            answerInput.setAttribute("placeholder", "PAST PARTICIPLE")
                        }
                    }, // End of success
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(textStatus);
                    }
                });
            }
        });
        $("#playPronunciation").click(function(event) {
            event.preventDefault()
            var elm = event.target;
            var audio = document.getElementById('audio');
            var source = document.getElementById('audioSource');
            source.src = elm.getAttribute('data-data-value');
            audio.load(); //call this to just preload the audio without playing
            audio.play(); //call this to play the song right away
        });

        $("#playPronunciationTwo").click(function(event) {
            event.preventDefault()
            var elm = event.target;
            var audio = document.getElementById('audio');
            var source = document.getElementById('audioSource');
            source.src = elm.getAttribute('data-data-value');
            audio.load(); //call this to just preload the audio without playing
            audio.play(); //call this to play the song right away
        });

        $("#playPronunciationThree").click(function(event) {
            event.preventDefault()
            var elm = event.target;
            var audio = document.getElementById('audio');
            var source = document.getElementById('audioSource');
            source.src = elm.getAttribute('data-data-value');
            audio.load(); //call this to just preload the audio without playing
            audio.play(); //call this to play the song right away
        });
    });
</script>

<?php
include("footer.php")
?>