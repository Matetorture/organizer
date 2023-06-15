$(document).ready(function() {

    const board = document.querySelectorAll('.board-list-board');
    board.forEach((e) => {
        const edit = e.querySelector('.edit-button');
        if(edit!=null){
            const boardName = e.querySelector('.list-board-name-name');
            edit.addEventListener('click', () => {
                const boardNameInput = document.createElement('input');
                boardNameInput.type = 'text';
                boardNameInput.value = boardName.textContent;
                boardNameInput.style.border = 'none';
                boardNameInput.style.height = '20px';
                boardNameInput.style.width = `${(10*boardName.textContent.length)+2}px`;
                boardName.replaceWith(boardNameInput);
                boardNameInput.focus();

                boardNameInput.addEventListener('input', () => {
                    boardNameInput.style.width = `${(10*boardNameInput.value.length)+2}px`;
                });
                boardNameInput.addEventListener('blur', () => {
                    boardName.innerHTML = boardNameInput.value;
                    boardNameInput.replaceWith(boardName);
                    $('#update').load('handlers/update_board_name.php', {
                        id: parseInt(e.querySelector('meta[name="id"]').content), 
                        boardName: boardNameInput.value
                    }, 
                    function(){
                        console.log('saved board name');
                    });
                });
            });
        }
    });

});