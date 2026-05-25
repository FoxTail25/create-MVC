<?php

namespace Core;

/*
	Класс View (представление), формирует финальный облик страницы, которая будет возвращена пользователю по его запросу.
	*/

class View
{
	/*
	Возвращаем готовую для отображения страницу
	*/
	public function render(Page $page)
	{
		return $this->renderLayout($page, $this->renderView($page));
	}

	/*
	Вставляем представление (View), "внутрь" Layout
	*/
	private function renderLayout(Page $page, $content)
	{
		$layoutPath = $_SERVER['DOCUMENT_ROOT'] . "/project/layouts/{$page->layout}.php";

		if (file_exists($layoutPath)) {
			ob_start();
			$title = $page->title;
			include $layoutPath;
			return ob_get_clean();
		} else {
			echo "Layout file not found at path $layoutPath";
			die();
		}
	}

	/*
	Вставляем данные, "внутрь" предствления (View).
	*/
	private function renderView(Page $page)
	{
		if ($page->view) {
			$viewPath = $_SERVER['DOCUMENT_ROOT'] . "/project/views/{$page->view}.php";

			if (file_exists($viewPath)) {
				ob_start();
				$data = $page->data;
				extract($data);
				include $viewPath;
				return ob_get_clean();
			} else {
				echo "View file not found at path $viewPath";
				die();
			}
		}
	}
}
