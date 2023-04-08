$(document).ready(function() {

    let isBoardOpen = document.querySelector('meta[name="isBoardOpen"]').content;

    $('#list').click(function(){
        $('#app').load('board_list.php');
    });
    
    if(!isBoardOpen){
        $('#app').load('board_list.php');
    }else{
        $('#app').load('board.php', function(){

            const board = {
                ref: document.querySelector("#board"),
                ctx: document.querySelector("#board").getContext("2d"),
                width: document.querySelector("#board").offsetWidth,
                height: document.querySelector("#board").offsetHeight
            };

            board.ctx.canvas.height = board.height;
            board.ctx.canvas.width = board.width;

            class Element {
                constructor(id, text, bgColor, textColor, x, y, categoryId){
                    this.id = id;
                    this.text = text;
                    this.bgColor = bgColor;
                    this.textColor = textColor;
                    this.x = x;
                    this.y = y;
                    this.categoryId = categoryId;
                    this.width = (13*this.text.length)+40;
                    this.height = 70;
                }
            }
            class Category {
                constructor(id, name, color){
                    this.id = id;
                    this.name = name;
                    this.color = color;
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
                        parseInt(document.querySelector(`meta[name="idc${i}"]`).content)
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
                    )
                );
            }

            function updateInDB(){
                $('#update').load('update.php', { 
                    elements: elements,
                    categories: categories
                }, 
                function(){
                    console.log('saved');
                });
            }

            let selectedElement = null;
            let mouseX;
            let mouseY;
            let deltaX;
            let deltaY;

            function drawBoard(){

                board.ctx.clearRect(0, 0, board.ref.width, board.ref.height);

                board.ctx.fillStyle = "#131313";
                board.ctx.fillRect(0, 0, board.width, board.height);

                elements.forEach((e) => {

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
                    board.ctx.font = "24px 'Share Tech Mono', monospace";
                    board.ctx.fillText(e.text, parseFloat(e.x)+e.width/2, parseFloat(e.y)+e.height/2+13/2);
                });

                updateInDB();
                drawElementsList();
            }

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

                    drawBoard();
                }
            }
            function handleMouseUp() { selectedElement = null; }

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

                        drawBoard();
                        board.ctx.fillStyle = `#${e.bgColor}`;
                        board.ctx.beginPath();
                        board.ctx.rect(e.x, e.y, e.width, e.height);
                        board.ctx.fill();
                    });

                    textInput.addEventListener('blur', () => {
                        e.text = textInput.value;
                        board.ref.parentNode.removeChild(textInput);
                        drawBoard();
                    });
                
                    break;
                    }
                }
            }

            function drawElementsList(){

                const list = document.querySelector('#elements-list');
                let returnList = "";

                returnList += '<form action="handlers/category/add.php" method="get"><button id="addCategory" type="submit">Add Category</button></form>';

                returnList += `<ul>`;
                categories.forEach((e) => {
                    returnList += `<li class="li-categories" id="li-category${e.id}"><span id="ctext${e.id}">${e.name}</span><span class="attributes"><input type="color" name="cColor${e.id}" id="cColor${e.id}" value="#${e.color}"><form action="handlers/category/delete.php" method="get"><button class="deleteCategory" name="id" value="${e.id}" type="submit">D</button></form></span><ul>`;
                    elements.forEach((el) => {
                        if(e.id == el.categoryId){
                            returnList += `<li class="li-elements" draggable="true" id="li-element${el.id}"><span id="etext${el.id}">${el.text}</span><span class="attributes"><input type="color" name="bgColor${el.id}" id="bgColor${el.id}" value="#${el.bgColor}"><input type="color" name="textColor${el.id}" id="textColor${el.id}" value="#${el.textColor}"><form action="handlers/element/delete.php" method="get"><button class="deleteElement" name="id" value="${el.id}" type="submit">D</button></form></span></li>`;
                        }
                    });

                    returnList += `<li><form action="handlers/element/add.php" method="get"><button class="addElement" name="id" value="${e.id}" type="submit">Add Element</button></form></li>`;

                    returnList += `</ul></li>`;
                });
                returnList += `</ul>`;
                list.innerHTML = returnList;
                console.log(list);

                categories.forEach((e) => {
                    const cColor = document.querySelector(`#cColor${e.id}`);
                    cColor.addEventListener('input', () => {
                        e.color = cColor.value.slice(1);
                        drawBoard();
                    });

                    const cText = document.querySelector(`#ctext${e.id}`);
                    cText.addEventListener('click', () => {
                        const cTextInput = document.createElement('input');
                        cTextInput.type = 'text';
                        cTextInput.value = e.name;
                        cTextInput.style.width = `${(7*e.name.length)+4}px`;

                        cText.replaceWith(cTextInput);
                        cTextInput.focus();

                        cTextInput.addEventListener('blur', () => {
                            cText.innerText = cTextInput.value;
                            cTextInput.replaceWith(cText);
                            e.name = cTextInput.value;
                            drawBoard();
                        });
                    });


                    elements.forEach((el) => {
                        if(e.id == el.categoryId){
                            const bgColor = document.querySelector(`#bgColor${el.id}`);
                            bgColor.addEventListener('input', () => {
                                el.bgColor = bgColor.value.slice(1);
                                drawBoard();
                            });

                            const textColor = document.querySelector(`#textColor${el.id}`);
                            textColor.addEventListener('input', () => {
                                el.textColor = textColor.value.slice(1);
                                drawBoard();
                            });

                            const eText = document.querySelector(`#etext${el.id}`);
                            eText.addEventListener('click', () => {
                                const eTextInput = document.createElement('input');
                                eTextInput.type = 'text';
                                eTextInput.value = el.text;
                                eTextInput.style.width = `${(7*e.name.length)+4}px`;
                                eText.replaceWith(eTextInput);
                                eTextInput.focus();

                                eTextInput.addEventListener('blur', () => {
                                    eText.innerText = eTextInput.value;
                                    eTextInput.replaceWith(eText);
                                    el.text = eTextInput.value;
                                    drawBoard();
                                });
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
                        drawBoard();
                    });
                    li.addEventListener('dragover', (ev) => {
                        ev.preventDefault();
                    });
                });
            }

            board.ref.addEventListener('mousedown', handleMouseDown);
            board.ref.addEventListener('mousemove', handleMouseMove);
            board.ref.addEventListener('mouseup', handleMouseUp);
            board.ref.addEventListener('click', handleClick);

            drawBoard();
        });
    }
});