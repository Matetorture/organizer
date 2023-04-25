$(document).ready(function() {

    let isBoardOpen = document.querySelector('meta[name="isBoardOpen"]').content;
    
    if(!isBoardOpen){
        $('#app').load('board_list.php');
    }else{
        $('#app').load('board.php', function(){

            const board = {
                ref: document.querySelector("#board"),
                ctx: document.querySelector("#board").getContext("2d"),
                width: document.querySelector("#board").offsetWidth,
                height: document.querySelector("#board").offsetHeight,
                bg: document.querySelector('#boardBg').value.slice(1)
            };

            board.ctx.canvas.height = board.height;
            board.ctx.canvas.width = board.width;

            window.addEventListener('resize', () => {
                board.width = board.ref.offsetWidth;
                board.height = board.ref.offsetHeight;

                board.ctx.canvas.height = board.height;
                board.ctx.canvas.width = board.width;

                drawBoard(false, permissions.edit, permissions.editU);
            });
              

            class Element {
                constructor(id, text, bgColor, textColor, x, y, categoryId, layer){
                    this.id = id;
                    this.text = text;
                    this.bgColor = bgColor;
                    this.textColor = textColor;
                    this.x = x;
                    this.y = y;
                    this.categoryId = categoryId;
                    this.width = (13*this.text.length)+40;
                    this.height = 70;
                    this.layer = layer;
                }
            }
            class Category {
                constructor(id, name, color, layer){
                    this.id = id;
                    this.name = name;
                    this.color = color;
                    this.layer = layer;
                }
            }

            let howManyElements = document.querySelector('meta[name="howManyElements"]').content;
            let elements = [];

            for(let i = 0; i < howManyElements; i++){
                elements.push(
                    new Element(
                        parseInt(document.querySelector(`meta[name="id${i}"]`).content),
                        document.querySelector(`meta[name="text${i}"]`).content,
                        document.querySelector(`meta[name="bgColor${i}"]`).content,
                        document.querySelector(`meta[name="textColor${i}"]`).content,
                        parseInt(document.querySelector(`meta[name="x${i}"]`).content),
                        parseInt(document.querySelector(`meta[name="y${i}"]`).content),
                        parseInt(document.querySelector(`meta[name="idc${i}"]`).content),
                        parseInt(document.querySelector(`meta[name="layer${i}"]`).content)
                    )
                );
            }

            let howManyCategories = document.querySelector('meta[name="howManyCategories"]').content;
            let categories = [];

            for(let i = 0; i < howManyCategories; i++){
                categories.push(
                    new Category(
                        parseInt(document.querySelector(`meta[name="cid${i}"]`).content),
                        document.querySelector(`meta[name="cname${i}"]`).content,
                        document.querySelector(`meta[name="ccolor${i}"]`).content,
                        parseInt(document.querySelector(`meta[name="clayer${i}"]`).content)
                    )
                );
            }

            const permissions = {
                owner:  document.querySelector(`meta[name="owner"]`).content == 1 ? true : false,
                edit:  document.querySelector(`meta[name="edit"]`).content == 1 ? true : false,
                add:  document.querySelector(`meta[name="addusers"]`).content == 1 ? true : false,
                editU: document.querySelector(`meta[name="editusers"]`).content == 1 ? true : false,
                kick:  document.querySelector(`meta[name="kickusers"]`).content == 1 ? true : false
            };

            function drawBoard(save = true, editable = true, admin = true, list = true, whichU="all", elementsU=elements, categoriesU=categories){

                board.ctx.clearRect(0, 0, board.ref.width, board.ref.height);

                board.ctx.fillStyle = `#${board.bg}`;
                board.ctx.fillRect(0, 0, board.width, board.height);

                elements.slice().reverse().forEach((e) => {

                    e.width = (13*e.text.length)+40;
                    e.height = 70;

                    board.ctx.fillStyle = `#${categories.find((el) => { return el.id == e.categoryId; }).color}`;
                    board.ctx.beginPath();
                    board.ctx.rect(e.x, e.y-3, e.width, 3);
                    board.ctx.fill();
                    
                    board.ctx.fillStyle = `#${e.bgColor}`;
                    board.ctx.beginPath();
                    board.ctx.rect(e.x, e.y, e.width, e.height);
                    board.ctx.fill();
    
                    board.ctx.fillStyle = `#${e.textColor}`;
                    board.ctx.textAlign = 'center';
                    board.ctx.font = '24px "Share Tech Mono", monospace';
                    board.ctx.fillText(e.text, parseFloat(e.x)+e.width/2, parseFloat(e.y)+e.height/2+13/2);
                });
                if(save){
                    updateInDB(elementsU, categoriesU, whichU);
                }
                if(list){
                    drawElementsList(editable, admin);
                }
            }
            
            function drawElementsList(editable = true, admin = true){

                const list = document.querySelector('#left-panel');
                let returnList = "";

                returnList += '<form action="handlers/category/add.php" method="get"><button id="addCategory" type="submit">Add Category</button></form>';
                if(admin){
                    returnList += '<button id="showUsers">USERS</button>';
                }
                returnList += '<div id="elements-list">';

                returnList += `<ul>`;
                categories.forEach((e) => {
                    let newCategory = e.name.substring(0, 40);
                            if(newCategory != e.name){
                                newCategory+='...';
                            }
                    returnList += `<li class="li-categories" id="li-category${e.id}"> <input class="layers" type="number" id="clayer${e.id}" value="${e.layer}"> <span id="ctext${e.id}">${newCategory}</span><span class="attributes"><input type="color" name="cColor${e.id}" id="cColor${e.id}" value="#${e.color}"><form action="handlers/category/delete.php" method="get"><button class="deleteCategory" name="id" value="${e.id}" type="submit"><img src="src/svg/delete.svg" alt="delete" width="24px" height="24px" style="border: none; padding: 0; margin: 0;"></button></form></span><ul>`;
                    elements.forEach((el) => {
                        if(e.id == el.categoryId){
                            let newText = el.text.substring(0, 20);
                            if(newText != el.text){
                                newText+='...';
                            }
                            returnList += `<li class="li-elements" draggable="true" id="li-element${el.id}"> <input class="layers" type="number" id="elayer${el.id}" value="${el.layer}"> <span id="etext${el.id}">${newText}</span><span class="attributes"> <span id="ex${el.id}">${el.x}</span> <span id="ey${el.id}">${el.y}</span> <input type="color" name="bgColor${el.id}" id="bgColor${el.id}" value="#${el.bgColor}"><input type="color" name="textColor${el.id}" id="textColor${el.id}" value="#${el.textColor}"><form action="handlers/element/delete.php" method="get"><button class="deleteElement" name="id" value="${el.id}" type="submit"><img src="src/svg/delete.svg" alt="delete" width="24px" height="24px" style="border: none; padding: 0; margin: 0;"></button></form></span></li>`;
                        }
                    });

                    returnList += `<li><form action="handlers/element/add.php" method="get"><button class="addElement" name="id" value="${e.id}" type="submit">Add Element</button></form></li>`;

                    returnList += `</ul></li>`;
                });
                returnList += `</ul></div>`;
                list.innerHTML = returnList;

                const layers = document.querySelectorAll('.layers');
                layers.forEach((e) => {
                    e.style.width = `${ 8*String(e.value).length }px`;
                });

                if(admin){
                    const userList = document.querySelectorAll('#users-list-list li span:last-child');

                    userList.forEach((e) => {
                        class User {
                            constructor(id, edit, addUsers, editUsers, kickUsers){
                                this.id = id;
                                this.edit = edit;
                                this.addUsers = addUsers;
                                this.editUsers = editUsers;
                                this.kickUsers = kickUsers;
                            }
                        }

                        const inputs = e.querySelectorAll('input');
                        let users =
                            new User(
                                parseInt(inputs[0].name.slice(4)),
                                inputs[0].checked ? 1 : 0,
                                inputs[1].checked ? 1 : 0,
                                inputs[2].checked ? 1 : 0,
                                inputs[3].checked ? 1 : 0
                            )
                        

                        inputs.forEach((el) => {
                            el.addEventListener('input', () => {
                                users.edit = inputs[0].checked ? 1 : 0;
                                users.addUsers = inputs[1].checked ? 1 : 0;
                                users.editUsers = inputs[2].checked ? 1 : 0;
                                users.kickUsers = inputs[3].checked ? 1 : 0;

                                $('#update').load('handlers/update_users.php', { 
                                    users: users
                                }, 
                                function(){
                                    console.log('saved users');
                                });
                            });
                        });
                    });

                    document.querySelector('#showUsers').addEventListener('click', () => {
                        document.querySelector('#users-list').style.display = "block";
                        document.querySelector('#users-list-bg').style.display = "block";
                    });
                    document.querySelector('#close-user-list').addEventListener('click', () => {
                        document.querySelector('#users-list').style.display = "none";
                        document.querySelector('#users-list-bg').style.display = "none";
                    });
                    document.querySelector('#users-list-bg').addEventListener('click', () => {
                        document.querySelector('#users-list').style.display = "none";
                        document.querySelector('#users-list-bg').style.display = "none";
                    });
                }

                if(editable){
                    categories.forEach((e) => {
                        const cColor = document.querySelector(`#cColor${e.id}`);
                        cColor.addEventListener('input', () => {
                            e.color = cColor.value.slice(1);
                            drawBoard(false, permissions.edit, permissions.editU, false);
                        });
    
                        const cText = document.querySelector(`#ctext${e.id}`);
                        cText.addEventListener('click', () => {
                            const cTextInput = document.createElement('input');
                            cTextInput.type = 'text';
                            cTextInput.value = e.name;
                            cTextInput.style.width = `${(8*e.name.length)+2}px`;
    
                            cText.replaceWith(cTextInput);
                            cTextInput.focus();
    
                            cTextInput.addEventListener('blur', () => {
                                cText.innerText = cTextInput.value;
                                cTextInput.replaceWith(cText);
                                e.name = cTextInput.value;
                                drawBoard(true, permissions.edit, permissions.editU, true, "category", '', e);
                            });
                        });

                        const cLayer = document.querySelector(`#clayer${e.id}`);
                        cLayer.addEventListener('blur', () => {
                            e.layer = cLayer.value;
                            drawBoard(true, permissions.edit, permissions.editU,true, "category", '', e);
                        });
    
    
                        elements.forEach((el) => {
                            if(e.id == el.categoryId){
                                const bgColor = document.querySelector(`#bgColor${el.id}`);
                                bgColor.addEventListener('input', () => {
                                    el.bgColor = bgColor.value.slice(1);
                                    drawBoard(false, permissions.edit, permissions.editU, false);
                                });
    
                                const textColor = document.querySelector(`#textColor${el.id}`);
                                textColor.addEventListener('input', () => {
                                    el.textColor = textColor.value.slice(1);
                                    drawBoard(false, permissions.edit, permissions.editU, false);
                                });
    
                                const eText = document.querySelector(`#etext${el.id}`);
                                eText.addEventListener('click', () => {
                                    const eTextInput = document.createElement('input');
                                    eTextInput.type = 'text';
                                    eTextInput.value = el.text;
                                    eTextInput.style.width = `${(8*el.text.length)+4}px`;
                                    eText.replaceWith(eTextInput);
                                    eTextInput.focus();

                                    eTextInput.addEventListener('input', () => {
                                        el.text = eTextInput.value;
                                        drawBoard(false, permissions.edit, permissions.editU, false);
                                    });
    
                                    eTextInput.addEventListener('blur', () => {
                                        eText.innerText = eTextInput.value;
                                        eTextInput.replaceWith(eText);
                                        el.text = eTextInput.value;
                                        drawBoard(true, permissions.edit, permissions.editU, true, 'element', el, '');
                                    });
                                });
    
                                const eX = document.querySelector(`#ex${el.id}`);
                                eX.addEventListener('click', () => {
                                    const eXInput = document.createElement('input');
                                    eXInput.type = 'text';
                                    eXInput.value = el.x;
                                    eXInput.style.width = `${ 8*String(el.x).length }px`;
                                    eX.replaceWith(eXInput);
                                    eXInput.focus();
    
                                    eXInput.addEventListener('blur', () => {
                                        eX.innerText = eXInput.value;
                                        eXInput.replaceWith(eX);
                                        el.x = parseInt(eXInput.value);
                                        drawBoard(true, permissions.edit, permissions.editU, true, 'element', el, '');
                                    });
                                });
    
                                const eY = document.querySelector(`#ey${el.id}`);
                                eY.addEventListener('click', () => {
                                    const eYInput = document.createElement('input');
                                    eYInput.type = 'text';
                                    eYInput.value = el.y;
                                    eYInput.style.width = `${ 8*String(el.y).length}px`;
                                    eY.replaceWith(eYInput);
                                    eYInput.focus();
    
                                    eYInput.addEventListener('blur', () => {
                                        eY.innerText = eYInput.value;
                                        eYInput.replaceWith(eY);
                                        el.y = parseInt(eYInput.value);
                                        drawBoard(true, permissions.edit, permissions.editU), true, 'element', el, '';
                                    });
                                });

                                const eLayer = document.querySelector(`#elayer${el.id}`);
                                eLayer.addEventListener('blur', () => {
                                    el.layer = eLayer.value;
                                    drawBoard(true, permissions.edit, permissions.editU, true, 'element', el, '');
                                });
                            }
                        });
    
                    });
    
                    var draggedElement;
    
                    document.querySelectorAll('.li-elements').forEach(li => {
                        li.addEventListener('dragstart', (ev) => {
                            draggedElement = ev.target;
                        });
                    });
    
                    document.querySelectorAll('.li-categories').forEach(li => {
                        li.addEventListener('drop', (ev) => {
                            ev.preventDefault();
                            var targetElement = ev.target.closest('.li-categories');
                            elements.find((el) => { return el.id == draggedElement.id.slice(10); }).categoryId = parseInt(targetElement.id.slice(11));
                            drawBoard(true, permissions.edit, permissions.editU, true, 'element', el, '');
                        });
                        li.addEventListener('dragover', (ev) => {
                            ev.preventDefault();
                        });
                    });
                }
            }
            if(!permissions.edit){
                drawBoard(false, permissions.edit, permissions.editU);
            }
            else{
                function updateInDB(elementsUp, categoriesUp, whichUp="all"){
                    $('#update').load('handlers/update_board.php', { 
                        elements: elementsUp,
                        categories: categoriesUp,
                        boardBg: board.bg,
                        which: whichUp,
                        id: 0
                    }, 
                    function(){
                        console.log('saved board');
                    });
                }
    
                let selectedElement = null;
                let mouseX;
                let mouseY;
                let deltaX;
                let deltaY;
    
                function handleMouseDown(event) {
                    mouseX = event.clientX - board.ref.offsetLeft;
                    mouseY = event.clientY - board.ref.offsetTop;
    
                    elements.forEach(function(e) {
                        if (mouseX > e.x && mouseX < e.x + e.width && mouseY > e.y && mouseY < e.y + e.height) {
                            selectedElement = e;
                            deltaX = mouseX - selectedElement.x;
                            deltaY = mouseY - selectedElement.y;
                        }
                    });
                }
                function handleMouseMove(event) {
                    if (selectedElement !== null) {
                        let newMouseX = event.clientX - board.ref.offsetLeft;
                        let newMouseY = event.clientY - board.ref.offsetTop;
                        
                        selectedElement.x = newMouseX - deltaX;
                        selectedElement.y = newMouseY - deltaY;
    
                        drawBoard(false, permissions.edit, permissions.editU);
                    }
                }
                function handleMouseUp() { 
                    selectedElement = null; 
                    drawBoard(true, permissions.edit, permissions.editU);
                }
    
                function handleClick(event) {
                    const rect = board.ref.getBoundingClientRect();
                    x = event.clientX - rect.left;
                    y = event.clientY - rect.top;
    
                    for (let i = 0; i < elements.length; i++) {
                        const e = elements[i];
                    
                        if (x >= e.x && x <= e.x + e.width && y >= e.y && y <= e.y + e.height) {
                        const textInput = document.createElement('input');
                        textInput.id = `textS`;
                        textInput.value = e.text;
                        
                        e.width = (13*e.text.length)+40;
                        e.height = 70;
    
                        board.ctx.fillStyle = `#${e.bgColor}`;
                        board.ctx.beginPath();
                        board.ctx.rect(e.x, e.y, e.width, e.height);
                        board.ctx.fill();
    
                        
    
                        textInput.type = 'text';
                        textInput.style.position = 'absolute';
                        textInput.style.left = board.ref.offsetLeft + e.x + 15 + 'px';
                        textInput.style.top = board.ref.offsetTop + e.y + 15 + 'px';
                        textInput.style.width = e.width - 30 + 'px';
                        textInput.style.height = e.height - 30 + 'px';
                        textInput.style.border = 'none';
                        textInput.style.padding = '0';
                        textInput.style.margin = '0';
                        textInput.style.color = '#ffffff'
                        textInput.style.fontSize = '24px';
                        textInput.style.fontFamily = "'Share Tech Mono', monospace";
                        textInput.style.backgroundColor = 'transparent';
    
                        board.ref.parentNode.appendChild(textInput);
                        textInput.focus();
                    
                        textInput.addEventListener('input', () => {
                            e.text = textInput.value;
                            
                            e.width = (13*e.text.length)+40;
                            e.height = 70;
    
                            textInput.style.width = e.width - 30 + 'px';
                            textInput.style.height = e.height - 30 + 'px';
    
                            drawBoard(false, permissions.edit, permissions.editU);
                            board.ctx.fillStyle = `#${e.bgColor}`;
                            board.ctx.beginPath();
                            board.ctx.rect(e.x, e.y, e.width, e.height);
                            board.ctx.fill();
                        });
    
                        textInput.addEventListener('blur', () => {
                            e.text = textInput.value;
                            board.ref.parentNode.removeChild(textInput);
                            drawBoard(true, permissions.edit, permissions.editU);
                        });
                    
                        break;
                        }
                    }
                }

                const boardBg = document.querySelector('#boardBg');
                boardBg.addEventListener('input', () => {
                    board.bg = boardBg.value.slice(1);
                    drawBoard(false, permissions.edit, permissions.editU);
                });


                const save = document.querySelector('#save');
                save.addEventListener('click', () => {
                    drawBoard(true, permissions.edit, permissions.editU);
                });
    
                board.ref.addEventListener('mousedown', handleMouseDown);
                board.ref.addEventListener('mousemove', handleMouseMove);
                board.ref.addEventListener('mouseup', handleMouseUp);
                board.ref.addEventListener('click', handleClick);
    
                drawBoard(false, permissions.edit, permissions.editU);
            }
        });
    }
});