<?php

namespace App\Services;

use App\Services\WriteEntities\Writer;
use App\Services\ReadEntities\Reader;
use App\Enums\ReaderMode;
use App\Dtos\{ViewAuthorListDto, ViewAuthorDto, AddOrEditAuthorDto, AddOrEditBookDto, ViewBookDto, ViewReaderListDto, AddOrEditReaderDto, ViewReaderDto, ViewBookToReaderDto, AddOrRemoveBookToReaderDto};
use App\Annotations\ReadingEntity;
use ReflectionClass;

class MainService 
{
    public function __construct(
            private AuthorService $authorService,
            private BookService $bookService,
            private ReaderService $readerService,
            private Writer $writer,
            private Reader $reader
    ){}
   
    public function start(): void
    {
        while (True){
            $this->processAction($this->getNextAction());
        }
    }
    
    private function getNextAction(): int
    {
        $action = '';
        
        while(!$action){
            $this->showActions();
            $action = readline('Введите номер действия: ');
            
            if($action){
                $action = trim($action);
            }
            
            $actionError = $this->getActionError($action);
            
            if($actionError){
                echo PHP_EOL.$actionError.PHP_EOL;
                $action = '';
            }
        }
        
        return intval($action);
    }
    
    private function processAction(int $action): void
    {
        switch($action){
            case 1:
                $this->writer->writeEntities($this->authorService->getAuthors(), ViewAuthorListDto::class);
                break;
            case 2:
                $this->writer->writeEntity(
                        $this->authorService->getAuthor(
                                $this->reader->readEntity(
                                        AddOrEditAuthorDto::class, 
                                        ReaderMode::MODE_VIEW,
                                        fn($id) => $this->authorService->isAuthorExists($id)
                                )
                        ), 
                        ViewAuthorDto::class
                );
                break;
            case 3:
               $result = $this->authorService->addOrEditAuthor(
                              $this->reader->readEntity(AddOrEditAuthorDto::class, ReaderMode::MODE_CREATE)    
               );
               $this->writeResultOfAddOrEdit(AddOrEditAuthorDto::class, $result);
               break;
            case 4:
               $editDto = $this->reader->readEntity(
                       AddOrEditAuthorDto::class, 
                       ReaderMode::MODE_EDIT, 
                       fn($id) => $this->authorService->isAuthorExists($id)
               ); 
               $result = $this->authorService->addOrEditAuthor($editDto);
               $this->writeResultOfAddOrEdit(AddOrEditAuthorDto::class, $result, true);
               break;
            case 5:
               $authorDto =  $this->reader->readEntity(
                       AddOrEditAuthorDto::class, 
                       ReaderMode::MODE_DELETE,
                       fn($id) => $this->authorService->isAuthorExists($id)
                );
               
               if($authorDto && $this->checkRemoveEntity(AddOrEditAuthorDto::class, $authorDto->getId())){
                     $result = $this->authorService->removeAuthor($authorDto);
                     $this->writeResultOfRemove(AddOrEditAuthorDto::class);
               }
               break;
            case 6:
                $this->writer->writeEntities($this->bookService->getBooks(), ViewBookDto::class);
                break;
            case 7:
                $this->writer->writeEntity(
                        $this->bookService->getBook(
                                $this->reader->readEntity(
                                        AddOrEditBookDto::class, 
                                        ReaderMode::MODE_VIEW,
                                        fn($id) => $this->bookService->isBookExists($id)
                                )
                        ), 
                        ViewBookDto::class
                );
                break;
            case 8:
               $result = $this->bookService->addOrEditBook(
                              $this->reader->readEntity(AddOrEditBookDto::class, ReaderMode::MODE_CREATE)    
               );
               $this->writeResultOfAddOrEdit(AddOrEditBookDto::class, $result);
               break;
            case 9:
               $editDto = $this->reader->readEntity(
                       AddOrEditBookDto::class, 
                       ReaderMode::MODE_EDIT,
                       fn($id) => $this->bookService->isBookExists($id)
               );
               $result = $this->bookService->addOrEditBook($editDto);
               $this->writeResultOfAddOrEdit(AddOrEditBookDto::class, $result, true);
               break;
            case 10:
               $bookDto =  $this->reader->readEntity(
                       AddOrEditBookDto::class, 
                       ReaderMode::MODE_DELETE,
                       fn($id) => $this->bookService->isBookExists($id)
                );
                
               if($bookDto && $this->checkRemoveEntity(AddOrEditBookDto::class, $bookDto->getId())){
                     $result = $this->bookService->removeBook($bookDto);
                     $this->writeResultOfRemove(AddOrEditBookDto::class);
               }
               break;
            case 11:
                $this->writer->writeEntities($this->readerService->getReaders(), ViewReaderListDto::class);
                break;
            case 12:
                $this->writer->writeEntity(
                        $this->readerService->getReader(
                                $this->reader->readEntity(
                                        AddOrEditReaderDto::class, 
                                        ReaderMode::MODE_VIEW,
                                        fn($id) => $this->readerService->isReaderExists($id)
                                )
                        ), 
                        ViewReaderDto::class
                );
                break;
            case 13:
               $result = $this->readerService->addOrEditReader(
                              $this->reader->readEntity(AddOrEditReaderDto::class, ReaderMode::MODE_CREATE)    
               );
               $this->writeResultOfAddOrEdit(AddOrEditReaderDto::class, $result);
               break;
            case 14:
               $editDto = $this->reader->readEntity(
                       AddOrEditReaderDto::class, 
                       ReaderMode::MODE_EDIT,
                       fn($id) => $this->readerService->isReaderExists($id)
               );
               $result = $this->readerService->addOrEditReader($editDto);
               $this->writeResultOfAddOrEdit(AddOrEditReaderDto::class, $result, true);
               break;
            case 15:
               $readerDto =  $this->reader->readEntity(
                       AddOrEditReaderDto::class, 
                       ReaderMode::MODE_DELETE,
                        fn($id) => $this->readerService->isReaderExists($id)
                );
                
               if($readerDto && $this->checkRemoveEntity(AddOrEditReaderDto::class, $readerDto->getId())){
                     $result = $this->readerService->removeReader($readerDto);
                     $this->writeResultOfRemove(AddOrEditReaderDto::class);
               }
               break;
            case 16:
               $this->writer->writeEntities($this->bookService->getReservedBooks(), ViewBookToReaderDto::class);
               break;
            case 17:
                $bookReservation =  $this->reader->readEntity(AddOrRemoveBookToReaderDto::class, ReaderMode::MODE_CREATE);
                
                if(!$bookReservation){
                    break;
                }
                
                if(!$this->bookService->isBookCanBeReserved($bookReservation->getBookId())){
                    echo PHP_EOL.'Данная книга не может быть зарезервирована, так как отсутствуют свободные экземпляры'.PHP_EOL;
                    break;
                }
                
                $reservationResult = $this->bookService->reserveBook(
                         $bookReservation
                );
                
                if($reservationResult){
                    echo PHP_EOL.'Книга успешно забронирована'.PHP_EOL;
                } else {
                    echo PHP_EOL.'При бронировании книги произошла ошибка, не найдена книга или клиент библиотеки. Проверьте данные и добавьте бронь ещё раз.'.PHP_EOL;
                }
                break;
            case 18:
                $unreserveDto = $this->reader->readEntity(AddOrRemoveBookToReaderDto::class, ReaderMode::MODE_DELETE);
                
                if($unreserveDto){
                        echo 'Вы уверены, что хотите снять бронь (процесс являтся необратимым) (да\нет)?: '.PHP_EOL;
                        
                        if(readline() == 'да'){
                            $this->bookService->removeReservationOfBook(
                                     $unreserveDto 
                            );
                            echo 'Бронь успешно удалена'.PHP_EOL;
                        }
                }
                break;
            case 19:
                exit();
            default:
                break;
        }
        
        echo PHP_EOL;
    }
    
    private function showActions(): void
    {
        echo <<<ACTIONS
        Выберите действие (введите номер из списка):
        1 - Просмотреть список авторов книг
        2 - Просмотреть автора книг по его ID
        3 - Добавить автора книг
        4 - Обновить автора книг
        5 - Удалить автора книг
        6 - Просмотреть список книг
        7 - Просмотреть книгу по её ID
        8 - Добавить книгу
        9 - Обновить книгу
        10 - Удалить книгу
        11 - Просмотреть список клиентов библиотеки
        12 - Просмотреть клиента библиотеки по его ID
        13 - Добавить клиента библиотеки
        14 - Обновить клиента библиотеки
        15 - Удалить клиента библиотеки
        16 - Посмотреть список забронированных книг
        17 - Забронировать книгу
        18 - Снять бронь книги
        19 - Выйти из программы
        ACTIONS;
        echo PHP_EOL;
    }
    
    private function readAction(): int
    {
        
    }
    
    private function getActionError(?string $action): string
    {
        if(!$action){
            return 'Номер действия не может быть пустым';
        }
        
        if(!is_numeric($action)){
            return 'Номер действия должен быть числом, большим нуля';
        }
        
        if(!in_array(intval($action), [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19])){
            return 'Номер действия должен быть целым числом от 1 до 19';
        }
        
        return '';
    }
    
    private function writeResultOfAddOrEdit(string $entityClassName, mixed $result, bool $isEdit = false): void
    {
         $entityName = $this->getEntityName($entityClassName);
        
        if($result){
            if($isEdit){
               echo PHP_EOL."Сущность \"$entityName\" упешно обновлена и сохранена в базе.".PHP_EOL;
            } else {
               echo PHP_EOL."Сущность \"$entityName\" упешно создана и сохранена в базе.".PHP_EOL;
            }
        } else {
            if(!$isEdit){
                echo PHP_EOL."Ошибка при создании сущности \"$entityName\". Попробуйте создать её ещё раз или обратитесь к администратору.".PHP_EOL; 
            }
        }
    }
    
    private function writeResultOfRemove(string $entityClassName): void
    {
        $entityName = $this->getEntityName($entityClassName);
        echo PHP_EOL."Сущность \"$entityName\" упешно удалена из базы.".PHP_EOL;
    }
    
    private function checkRemoveEntity(string $entityClassName, int $entityId): bool
    {
         $entityName = $this->getEntityName($entityClassName);
         echo "Вы уверены, что хотите удалить сущность \"$entityName\"  с ID $entityId? Процесс является необратимым (да\нет): \n";
         return readline('') == 'да';
    }
    
    private function getEntityName(string $entityClassName): string
    {
        $entityClass = new ReflectionClass($entityClassName);
        return ($entityClass->getAttributes(ReadingEntity::class)[0])->newInstance()->displayName;
    }
}
