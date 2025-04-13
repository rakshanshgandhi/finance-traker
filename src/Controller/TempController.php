<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionFormType;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TempController extends AbstractController
{
    private $transactionRepository;
    private $entityManager;

    // Inject EntityManagerInterface
    public function __construct(TransactionRepository $transactionRepository, EntityManagerInterface $entityManager)
    {
        $this->transactionRepository = $transactionRepository;
        $this->entityManager = $entityManager;  // Assign to the property
    }

    #[Route('/temp', name: 'app_temp')]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig');
    }

    #[Route('/show', name: 'app_show')]
    public function show(): Response
    {
        // Get the currently authenticated user
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user) {
            // Redirect to login page or show an error if the user is not authenticated
            return $this->redirectToRoute('app_login');
        }

        // Fetch transactions for the logged-in user
        $transactions = $this->transactionRepository->findBy(['user' => $user]);

        return $this->render('transactions/show.html.twig', [
            'transactions' => $transactions,
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request): Response
    {
        // Get the currently authenticated user
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user) {
            // Redirect to login page or show an error if the user is not authenticated
            return $this->redirectToRoute('app_login');
        }
        $transaction = new Transaction();

        $form = $this->createForm(TransactionFormType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set the authenticated user manually (if not done automatically by the form)
            $user = $this->getUser();
            if ($user) {
                $transaction->setUser($user);
            }

            // Persist and flush the transaction to the database
            $this->entityManager->persist($transaction);
            $this->entityManager->flush();

            $this->addFlash('success', 'Transaction added successfully!');
            return $this->redirectToRoute('app_show');
        }
        return $this->render('transactions/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit_transaction')]
    public function edit($id, Request $request)
    {
        // Get the currently logged-in user
        $user = $this->getUser(); // Assumes you're using Symfony's security component

        // Find the transaction by ID
        $transaction = $this->transactionRepository->find($id);

        // If the transaction doesn't exist, or it doesn't belong to the current user
        if (!$transaction) {
            $this->addFlash('error', 'Transaction not found');
            return $this->redirectToRoute('app_show');
        }

        // Ensure that the logged-in user is the owner of the transaction
        if ($transaction->getUser() !== $user) {
            $this->addFlash('error', 'You are not authorized to edit this transaction.');
            return $this->redirectToRoute('app_show');
        }

        // Create and handle the form to edit the transaction
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the edited transaction
            $this->entityManager->flush();
            $this->addFlash('success', 'Transaction is edited successfully!');
            // Redirect back to the transactions list after successful edit
            return $this->redirectToRoute('app_show');
        }

        // Render the edit form
        return $this->render('transactions/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_transaction', methods: ['POST'])]
    public function delete($id, Request $request): Response
    {
        $user = $this->getUser();
        $transaction = $this->transactionRepository->find($id);

        if (!$transaction || $transaction->getUser() !== $user) {
            $this->addFlash('error', 'Transaction not found or you are not authorized to delete it.');
            return $this->redirectToRoute('app_show');
        }

        // Verify CSRF token
        $csrfToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete' . $transaction->getId(), $csrfToken)) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_show');
        }

        $this->entityManager->remove($transaction);
        $this->entityManager->flush();

        $this->addFlash('success', 'Transaction deleted successfully.');
        return $this->redirectToRoute('app_show');
    }
    #[Route('/pie-chart', name: 'app_pie_chart')]
    public function transactionPieChart(): Response
    {
        // Get the currently authenticated user
        $user = $this->getUser();

        // Check if the user is logged in
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Query to get the sum of 'Income' and 'Expense' for the logged-in user
        $income = $this->transactionRepository->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->where('t.user = :user')
            ->andWhere('t.description = :description')
            ->setParameter('user', $user)
            ->setParameter('description', 'Income')
            ->getQuery()
            ->getSingleScalarResult();

        $expense = $this->transactionRepository->createQueryBuilder('t')
            ->select('SUM(t.amount)')
            ->where('t.user = :user')
            ->andWhere('t.description = :description')
            ->setParameter('user', $user)
            ->setParameter('description', 'Expense')
            ->getQuery()
            ->getSingleScalarResult();

        // If no transactions, set income and expense to 0
        $income = $income ?: 0;
        $expense = $expense ?: 0;

        // Pass the data to the Twig template
        return $this->render('transactions/pie_chart.html.twig', [
            'pieChartData' => [
                'Income' => $income,
                'Expense' => $expense,
            ]
        ]);
    }
}
