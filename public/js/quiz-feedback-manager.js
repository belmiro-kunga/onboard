/**
 * QuizFeedbackManager - Gerencia o feedback imediato para quizzes
 * 
 * Este componente gerencia o feedback imediato para quizzes, incluindo:
 * - Salvar respostas individuais
 * - Mostrar feedback visual
 * - Navegar para próxima questão
 * - Atualizar progresso
 */
class QuizFeedbackManager {
    /**
     * Inicializa o gerenciador de feedback
     * 
     * @param {number} quizId - ID do quiz
     * @param {number} attemptId - ID da tentativa
     */
    constructor(quizId, attemptId) {
        this.quizId = quizId;
        this.attemptId = attemptId;
        this.currentQuestionId = null;
        this.totalQuestions = 0;
        this.answeredQuestions = 0;
        this.correctAnswers = 0;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Inicializar eventos
        this.initEvents();
    }

    /**
     * Inicializa eventos para opções de resposta
     */
    initEvents() {
        // Obter elementos do DOM
        this.questionContainer = document.querySelector('.quiz-question-container');
        this.progressBar = document.querySelector('.quiz-progress-bar');
        this.progressText = document.querySelector('.quiz-progress-text');
        this.totalQuestionsElement = document.querySelector('.total-questions');
        
        if (this.totalQuestionsElement) {
            this.totalQuestions = parseInt(this.totalQuestionsElement.textContent);
        }
        
        // Adicionar eventos para opções de resposta
        document.querySelectorAll('.quiz-answer-option').forEach(option => {
            option.addEventListener('click', this.handleAnswerSelection.bind(this));
        });
        
        // Obter ID da questão atual
        const questionElement = document.querySelector('.quiz-question');
        if (questionElement) {
            this.currentQuestionId = questionElement.dataset.questionId;
        }
    }

    /**
     * Manipula a seleção de resposta
     * 
     * @param {Event} event - Evento de clique
     */
    handleAnswerSelection(event) {
        const option = event.currentTarget;
        const questionId = option.closest('.quiz-question').dataset.questionId;
        const answer = option.dataset.value;
        
        // Desabilitar todas as opções para evitar múltiplas seleções
        document.querySelectorAll('.quiz-answer-option').forEach(opt => {
            opt.classList.add('pointer-events-none', 'opacity-50');
        });
        
        // Adicionar classe de selecionado
        option.classList.remove('opacity-50');
        option.classList.add('selected');
        
        // Mostrar loader
        this.showLoader(option);
        
        // Salvar resposta e mostrar feedback
        this.saveAnswer(questionId, answer, option);
    }

    /**
     * Mostra loader durante o processamento
     * 
     * @param {HTMLElement} option - Elemento da opção selecionada
     */
    showLoader(option) {
        const loader = document.createElement('div');
        loader.className = 'answer-loader';
        loader.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        option.appendChild(loader);
    }

    /**
     * Salva a resposta e mostra feedback
     * 
     * @param {number} questionId - ID da questão
     * @param {string} answer - Resposta selecionada
     * @param {HTMLElement} option - Elemento da opção selecionada
     */
    saveAnswer(questionId, answer, option) {
        fetch(`/quizzes/${this.quizId}/attempt/${this.attemptId}/answer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                question_id: questionId,
                answer: answer
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remover loader
            option.querySelector('.answer-loader').remove();
            
            if (data.success) {
                // Mostrar feedback
                this.showFeedback(data.feedback, option);
                
                // Atualizar progresso
                this.updateProgress(data.progress);
            } else {
                // Mostrar erro
                this.showError(data.message || 'Erro ao salvar resposta');
                
                // Reativar opções
                document.querySelectorAll('.quiz-answer-option').forEach(opt => {
                    opt.classList.remove('pointer-events-none', 'opacity-50');
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            option.querySelector('.answer-loader').remove();
            this.showError('Erro de conexão. Tente novamente.');
            
            // Reativar opções
            document.querySelectorAll('.quiz-answer-option').forEach(opt => {
                opt.classList.remove('pointer-events-none', 'opacity-50');
            });
        });
    }

    /**
     * Mostra feedback visual
     * 
     * @param {Object} feedback - Dados de feedback
     * @param {HTMLElement} selectedOption - Elemento da opção selecionada
     */
    showFeedback(feedback, selectedOption) {
        // Destacar resposta correta e incorreta
        document.querySelectorAll('.quiz-answer-option').forEach(option => {
            const value = option.dataset.value;
            
            // Verificar se é a resposta correta
            if (this.isCorrectAnswer(value, feedback.correct_answer)) {
                option.classList.add('correct-answer');
                option.innerHTML += `
                    <span class="absolute right-3 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                `;
            } 
            // Verificar se é a resposta selecionada e está incorreta
            else if (value === feedback.selected_answer && !feedback.is_correct) {
                option.classList.add('incorrect-answer');
                option.innerHTML += `
                    <span class="absolute right-3 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                `;
            }
        });
        
        // Criar e mostrar card de feedback
        const feedbackCard = document.createElement('div');
        feedbackCard.className = `feedback-card mt-6 p-4 rounded-lg ${feedback.is_correct ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'}`;
        
        feedbackCard.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    ${feedback.is_correct 
                        ? '<span class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100"><svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></span>'
                        : '<span class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100"><svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></span>'
                    }
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium ${feedback.is_correct ? 'text-green-800' : 'text-red-800'}">
                        ${feedback.is_correct ? 'Resposta Correta!' : 'Resposta Incorreta'}
                    </h3>
                    <div class="mt-2 text-sm ${feedback.is_correct ? 'text-green-700' : 'text-red-700'}">
                        <p>${feedback.explanation || 'Sem explicação disponível.'}</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" class="next-question-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Próxima Questão
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        `;
        
        // Adicionar card de feedback após a questão
        const questionElement = document.querySelector('.quiz-question');
        questionElement.parentNode.insertBefore(feedbackCard, questionElement.nextSibling);
        
        // Adicionar evento para o botão de próxima questão
        document.querySelector('.next-question-btn').addEventListener('click', this.navigateToNext.bind(this));
        
        // Adicionar haptic feedback em dispositivos móveis
        if (window.navigator && window.navigator.vibrate) {
            window.navigator.vibrate(feedback.is_correct ? 100 : [100, 50, 100]);
        }
        
        // Atualizar contadores
        this.answeredQuestions++;
        if (feedback.is_correct) {
            this.correctAnswers++;
        }
    }

    /**
     * Verifica se a resposta é correta
     * 
     * @param {string} answer - Resposta a verificar
     * @param {Array|string} correctAnswer - Resposta(s) correta(s)
     * @returns {boolean} - Se a resposta é correta
     */
    isCorrectAnswer(answer, correctAnswer) {
        if (Array.isArray(correctAnswer)) {
            return correctAnswer.includes(answer);
        }
        return answer === correctAnswer;
    }

    /**
     * Mostra mensagem de erro
     * 
     * @param {string} message - Mensagem de erro
     */
    showError(message) {
        const errorToast = document.createElement('div');
        errorToast.className = 'error-toast fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50';
        errorToast.textContent = message;
        
        document.body.appendChild(errorToast);
        
        setTimeout(() => {
            errorToast.remove();
        }, 3000);
    }

    /**
     * Navega para a próxima questão
     */
    navigateToNext() {
        // Mostrar loader
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-center text-gray-700">Carregando próxima questão...</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
        
        // Requisitar próxima questão
        fetch(`/quizzes/${this.quizId}/attempt/${this.attemptId}/next`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.completed) {
                    // Redirecionar para página de resultados
                    window.location.href = data.redirect_url;
                } else {
                    // Atualizar conteúdo da página com próxima questão
                    this.loadNextQuestion(data.next_question);
                }
            } else {
                this.showError(data.message || 'Erro ao carregar próxima questão');
                loadingOverlay.remove();
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            this.showError('Erro de conexão. Tente novamente.');
            loadingOverlay.remove();
        });
    }

    /**
     * Carrega a próxima questão na interface
     * 
     * @param {Object} question - Dados da próxima questão
     */
    loadNextQuestion(question) {
        // Atualizar ID da questão atual
        this.currentQuestionId = question.id;
        
        // Criar HTML da nova questão
        let optionsHtml = '';
        
        if (question.question_type === 'multiple_choice') {
            question.options.forEach((option, index) => {
                const letter = String.fromCharCode(65 + index); // A, B, C, D...
                optionsHtml += `
                    <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="${letter}">
                        <div class="flex items-center">
                            <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">${letter}</span>
                            <span>${option}</span>
                        </div>
                    </div>
                `;
            });
        } else if (question.question_type === 'true_false') {
            optionsHtml = `
                <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="true">
                    <div class="flex items-center">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">V</span>
                        <span>Verdadeiro</span>
                    </div>
                </div>
                <div class="quiz-answer-option relative p-4 border rounded-lg mb-3 cursor-pointer hover:bg-gray-50 transition-colors" data-value="false">
                    <div class="flex items-center">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-indigo-100 text-indigo-800 font-medium text-sm mr-3">F</span>
                        <span>Falso</span>
                    </div>
                </div>
            `;
        }
        
        // Criar HTML completo da questão
        const questionHtml = `
            <div class="quiz-question" data-question-id="${question.id}">
                <h3 class="text-lg font-medium text-gray-900 mb-4">${question.question}</h3>
                <div class="quiz-options">
                    ${optionsHtml}
                </div>
            </div>
        `;
        
        // Atualizar conteúdo
        this.questionContainer.innerHTML = questionHtml;
        
        // Reinicializar eventos
        document.querySelectorAll('.quiz-answer-option').forEach(option => {
            option.addEventListener('click', this.handleAnswerSelection.bind(this));
        });
        
        // Remover overlay de carregamento
        document.querySelector('.fixed.inset-0.bg-gray-900').remove();
        
        // Rolar para o topo da questão
        window.scrollTo({
            top: this.questionContainer.offsetTop - 20,
            behavior: 'smooth'
        });
    }

    /**
     * Atualiza a barra de progresso
     * 
     * @param {Object} progress - Dados de progresso
     */
    updateProgress(progress) {
        if (!this.progressBar || !this.progressText) return;
        
        const percentage = progress.progress_percentage || 0;
        
        // Atualizar barra de progresso
        this.progressBar.style.width = `${percentage}%`;
        
        // Atualizar texto de progresso
        this.progressText.textContent = `${progress.current_question} de ${progress.total_questions} questões`;
        
        // Atualizar contadores
        this.answeredQuestions = progress.current_question;
        this.correctAnswers = progress.correct_answers;
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const quizContainer = document.querySelector('.quiz-container');
    
    if (quizContainer) {
        const quizId = quizContainer.dataset.quizId;
        const attemptId = quizContainer.dataset.attemptId;
        
        if (quizId && attemptId) {
            window.quizFeedbackManager = new QuizFeedbackManager(quizId, attemptId);
        }
    }
});