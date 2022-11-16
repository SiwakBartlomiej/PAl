<?php

namespace Application\Controller;

use Application\Form\AutorForm;
use Application\Form\UsunForm;
use Application\Model\Autor;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AutorzyController extends AbstractActionController
{
    public function __construct(public Autor $autor, public AutorForm $autorForm, public UsunForm $usunForm)
    {
    }

    public function listaAction()
    {
        return [
            'autor' => $this->autor->pobierzWszystko(),
        ];
    }

    public function dodajAction()
    {
        $this->autorForm->get('zapisz')->setValue('Dodaj');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->autorForm->setData($request->getPost());

            if ($this->autorForm->isValid()) {
                $this->autor->dodaj($request->getPost());

                return $this->redirect()->toRoute('autorzy');
            }
        }

        return ['tytul' => 'Dodawanie autora', 'form' => $this->autorForm];
    }

    public function edytujAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->autorForm->setData($request->getPost());

            if ($this->autorForm->isValid()) {
                $this->autor->aktualizuj($id, $request->getPost());

                return $this->redirect()->toRoute('autorzy');
            }
        } else {
            $daneAutora = $this->autor->pobierz($id);
            $this->autorForm->setData($daneAutora);
        }

        $viewModel = new ViewModel(['tytul' => 'Edytuj dane autora', 'form' => $this->autorForm]);
        $viewModel->setTemplate('application/autorzy/dodaj');

        return $viewModel;
    }

    public function usunAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $odpowiedz = $request->getPost();
            if ($odpowiedz["Tak"] == 'Tak') {
                $this->autor->usun($id, $request->getPost());
            }
            return $this->redirect()->toRoute('autorzy');
        }

        $imie = $this->autor->pobierz($id)->imie;
        $nazwisko = $this->autor->pobierz($id)->nazwisko;
        
        $viewModel = new ViewModel(['form' => $this->usunForm, 'imieNazwisko' => $imie . ' ' . $nazwisko]);
        $viewModel->setTemplate('application/autorzy/usun');

        return $viewModel;
    }

    public function szczegolyAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('autorzy');
        }

        $szczegoly = $this->autor->pobierz($id)->opis;
        $imie = $this->autor->pobierz($id)->imie;
        $nazwisko = $this->autor->pobierz($id)->nazwisko;

        $viewModel = new ViewModel(['imieNazwisko' => $imie . ' ' . $nazwisko, 'szczegoly' => $szczegoly]);
        $viewModel->setTemplate('application/autorzy/szczegoly');

        return $viewModel;
    }
}
