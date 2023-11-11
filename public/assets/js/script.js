




    const questions = {{ quizs | json_encode | raw }};
    console.log(questions);

    const questionElement = document.getElementById('question');
    const answerButtons = document.getElementById('answer-buttons');
    const nextButton = document.getElementById('next-btn');

    let currentQuestionIndex = 0;
    let score = 0;

    function startQuiz() {
        currentQuestionIndex = 0;
        score = 0;
        showQuestion();
    }

    function reset() {
        nextButton.style.display = "none";
        while (answerButtons.firstChild) {
            answerButtons.removeChild(answerButtons.firstChild);
        }
    }

    function showQuestion() {
        reset();
        let questionKey = Object.keys(questions)[currentQuestionIndex];
        let currentQuestion = questions[questionKey];
        console.log(currentQuestion);
        questionElement.innerHTML = currentQuestion.question;
        currentQuestion.answers.forEach((answer) => {
            const btn = document.createElement("button");
            btn.innerHTML = answer.text;
            answerButtons.appendChild(btn);
            if (answer.correct) {
                btn.dataset.correct = answer.correct;
            }
            btn.addEventListener("click", selectAnswer);
        });
    }

    function selectAnswer(event) {
        const selectBtn = event.target;
        const isCorrect = selectBtn.dataset.correct === "true";
        if (!isCorrect) {
            selectBtn.classList.add("incorrect");
        } else {
            selectBtn.classList.add("correct");
            score++;
        }
        Array.from(answerButtons.children).forEach(btn => {
            if (btn.dataset.correct === "true") {
                btn.classList.add("correct");
            }
            btn.disabled = true;
        });
        nextButton.style.display = "block";
    }

    function handleNextButton() {
        currentQuestionIndex++;
        if (currentQuestionIndex < Object.keys(questions).length) {
            showQuestion();
        } else {
            showScore();
        }
    }

    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex < Object.keys(questions).length) {
            handleNextButton();
        } else {
            startQuiz();
        }
    });

    function showScore() {
        reset();
        questionElement.innerHTML = `You scored ${score} out of ${Object.keys(questions).length} correct answers`;
        nextButton.innerHTML = "Play Again";
        nextButton.style.display = "block";
    }

    startQuiz();
