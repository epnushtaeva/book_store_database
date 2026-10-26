<?php

namespace App\Fabrics;

use App\Models\{Author, Book, Reader, BookToReader};
use App\Repositories\{AuthorRepository, BookRepository, BookToReaderRepository, ReaderRepository};

abstract class RepositoryFabric 
{
    private static ?AuthorRepository $authorRepository = null;
    private static ?BookRepository $bookRepository = null;
    private static ?BookToReaderRepository $bookToReaderRepository = null;
    private static ?ReaderRepository $readerRepository = null;
    private static $context = ['repositories' => []];
    
    public static function getAuthorRepository(): AuthorRepository 
    {
        if(!self::$authorRepository){
            self::$authorRepository = new AuthorRepository();
            self::updateContextInRepositories();
        }
        
        return self::$authorRepository;
    }

    public static function getBookRepository(): BookRepository 
    {
        if(!self::$bookRepository){
            self::$bookRepository = new BookRepository();
            self::updateContextInRepositories();
        }
        
        return self::$bookRepository;
    }

    public static function getBookToReaderRepository(): BookToReaderRepository 
    {
        if(!self::$bookToReaderRepository){
            self::$bookToReaderRepository = new BookToReaderRepository();
            self::updateContextInRepositories();
        }
        
        return self::$bookToReaderRepository;
    }

    public static function getReaderRepository(): ReaderRepository 
    {
        if(!self::$readerRepository){
            self::$readerRepository= new ReaderRepository();
             self::updateContextInRepositories();
        }
        
        return self::$readerRepository;
    }
    
    private static function updateContextInRepositories(): void
    {
        self::$context['repositories'] = [
            Author::class => self::$authorRepository,
            Book::class => self::$bookRepository,
            BookToReader::class => self::$bookToReaderRepository,
            Reader::class => self::$readerRepository
        ];
        
        if(self::$authorRepository){
            self::$authorRepository->setContext(self::$context);
        }
        
        if(self::$bookRepository){
            self::$bookRepository->setContext(self::$context);
        }
        
        if(self::$bookToReaderRepository){
            self::$bookToReaderRepository->setContext(self::$context);
        }
        
        if(self::$readerRepository){
            self::$readerRepository->setContext(self::$context);
        }
    }
}
