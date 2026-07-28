<?php

namespace App\Fabrics;

use App\Repositories\{AuthorRepository, BookRepository, BookToReaderRepository, ReaderRepository};

abstract class RepositoryFabric 
{
    private static ?AuthorRepository $authorRepository = null;
    private static ?BookRepository $bookRepository = null;
    private static ?BookToReaderRepository $bookToReaderRepository = null;
    private static ?ReaderRepository $readerRepository = null;
    
    public static function getAuthorRepository(): AuthorRepository 
    {
        if(!self::$authorRepository){
            self::$authorRepository = new AuthorRepository();
        }
        
        return self::$authorRepository;
    }

    public static function getBookRepository(): BookRepository 
    {
        if(!self::$bookRepository){
            self::$bookRepository = new BookRepository();
        }
        
        return self::$bookRepository;
    }

    public static function getBookToReaderRepository(): BookToReaderRepository 
    {
        if(!self::$bookToReaderRepository){
            self::$bookToReaderRepository = new BookToReaderRepository();
        }
        
        return self::$bookToReaderRepository;
    }

    public static function getReaderRepository(): ReaderRepository 
    {
        if(!self::$readerRepository){
            self::$readerRepository= new ReaderRepository();
        }
        
        return self::$readerRepository;
    }
}
