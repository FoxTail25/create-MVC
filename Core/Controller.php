<?php

namespace Core;
/*
Класс контроллер формирует данные, для ответа на запрос пользователя.
В качестве ответа, выступает экземпляр класс Page, который содержит в своих свойствах layout, title, путь к необходимому экземпляру класса View. А также может содеражать именнованный массив с данными, которые будут отражены в представлении (View).
*/

class Controller
{
    // Данные "по умолчанию" для layout и title
    public string $title = 'MVC-framework';
    public string $layout = 'default';

    protected function render(string $view, array $data = [])
    {
        return new Page($this->layout, $this->title, $view, $data);
    }
}
