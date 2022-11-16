<?php

namespace Application\Controller;

use Application\Form\KsiazkaForm;
use Application\Form\UsunForm;
use Application\Model\Ksiazka;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class KsiazkiController extends AbstractActionController
{
    public function __construct(public Ksiazka $ksiazka, public KsiazkaForm $ksiazkaForm,
        public UsunForm $usunForm)
    {
    }

    public function listaAction()
    {
        return [
            'ksiazki' => $this->ksiazka->pobierzWszystko(),
        ];
    }

    public function dodajAction()
    {
        $this->ksiazkaForm->get('zapisz')->setValue('Dodaj');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->ksiazkaForm->setData($request->getPost());

            if ($this->ksiazkaForm->isValid()) {
                $this->ksiazka->dodaj($request->getPost());

                return $this->redirect()->toRoute('ksiazki');
            }
        }

        return ['tytul' => 'Dodawanie książki', 'form' => $this->ksiazkaForm];
    }

    public function edytujAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('ksiazki');
        }

        $request = $this->getRequest();
        var_dump($request);
        if ($request->isPost()) {
            $this->ksiazkaForm->setData($request->getPost());

            if ($this->ksiazkaForm->isValid()) {
                $this->ksiazka->aktualizuj($id, $request->getPost());

                return $this->redirect()->toRoute('ksiazki');
            }
        } else {
            $daneKsiazki = $this->ksiazka->pobierz($id);
            $this->ksiazkaForm->setData($daneKsiazki);
        }

        $viewModel = new ViewModel(['tytul' => 'Edytuj książkę', 'form' => $this->ksiazkaForm]);
        $viewModel->setTemplate('application/ksiazki/dodaj');

        return $viewModel;
    }

    public function usunAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('ksiazki');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $odpowiedz = $request->getPost();
            if ($odpowiedz["Tak"] == 'Tak') {
                $this->ksiazka->usun($id, $request->getPost());
            }
            return $this->redirect()->toRoute('ksiazki');
        }
        
        $viewModel = new ViewModel(['form' => $this->usunForm, 'tytul' => $this->ksiazka->pobierz($id)->tytul]);
        $viewModel->setTemplate('application/ksiazki/usun');

        return $viewModel;
    }

    public function szczegolyAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (empty($id)) {
            $this->redirect()->toRoute('ksiazki');
        }

        $szczegoly = $this->ksiazka->pobierz($id)->opis;
        $tytul = $this->ksiazka->pobierz($id)->tytul;

        $viewModel = new ViewModel(['tytul' => $tytul, 'szczegoly' => $szczegoly]);
        $viewModel->setTemplate('application/ksiazki/szczegoly');

        return $viewModel;
    }
}
