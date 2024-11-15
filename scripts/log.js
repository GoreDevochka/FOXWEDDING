var loginModal = document.getElementById('loginModal');
    var registerModal = document.getElementById('registerModal');
    var loginBtn = document.getElementById('loginBtn');
    var registerQuestion = document.getElementById('registerQuestion');
    var closeButtons = document.getElementsByClassName('close');
    
    loginBtn.onclick = function() {
      loginModal.style.display = 'block';
    }
    
    registerQuestion.onclick = function() {
      loginModal.style.display = 'none';
      registerModal.style.display = 'block';
    }
    
    for (var i = 0; i < closeButtons.length; i++) {
      closeButtons[i].onclick = function() {
        this.parentElement.parentElement.style.display = 'none';
      }
    }
    
    window.onclick = function(event) {
      if (event.target == loginModal || event.target == registerModal) {
        event.target.style.display = 'none';
      }
    }