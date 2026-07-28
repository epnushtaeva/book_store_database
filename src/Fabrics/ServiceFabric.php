<?php

namespace App\Fabrics;

use App\Services\ReadEntities\{Reader, ConsoleReader};
use App\Services\WriteEntities\{Writer, ConsoleWriter};
use App\Services\{AuthorService, BookService, ReaderService, MainService};
use App\Services\AllowedValuesGetters\{GetAllowedAuthorsService, GetAllowedBooksService, GetAllowedCountryService, GetAllowedJanreService, GetAllowedLanguageService, GetAllowedReaderService};

abstract class ServiceFabric {
    private static ?AuthorService $authorService = null;
    private static ?BookService $bookService = null;
    private static ?ReaderService $readerService = null;
    private static ?MainService $mainService = null;
    private static ?GetAllowedAuthorsService $getAuthorsService = null;
    private static ?GetAllowedBooksService $getBooksService = null;
    private static ?GetAllowedCountryService $getCountryService = null;
    private static ?GetAllowedJanreService $getJanreService = null;
    private static ?GetAllowedLanguageService $getLanguageService = null;
    private static ?GetAllowedReaderService $getReaderService = null;

    public static function getBookService(): BookService 
    {
        if(!self::$bookService){
            self::$bookService= new BookService(
                        RepositoryFabric::getBookRepository(),
                        RepositoryFabric::getBookToReaderRepository(),
                        MapperFabric::getBookMapper(),
                        MapperFabric::getBookToReaderMapper()
            );
        }
        
        return self::$bookService;
    }
    
    public static function getAuthorService(): AuthorService
    {
        if(!self::$authorService){
            self::$authorService = new AuthorService(
                        RepositoryFabric::getAuthorRepository(),
                        MapperFabric::getAuthorMapper(),
                        self::getBookService()
            );
        }
        
        return self::$authorService;
    }
    
    public static function getReaderService(): ReaderService
    {
        if(!self::$readerService){
            self::$readerService = new ReaderService(
                                  RepositoryFabric::getReaderRepository(), 
                                  MapperFabric::getReaderMapper(), 
                                  self::getBookService()
            );
        }
        
        return self::$readerService;
    }
    
    public static function getReader(): Reader
    {
        return new ConsoleReader();
    }
    
    public static function getWriter(): Writer
    {
        return new ConsoleWriter();
    }
    
    public static function getMainService(): MainService
    {
        if(!self::$mainService){
            self::$mainService = new MainService(
                    self::getAuthorService(),
                    self::getBookService(),
                    self::getReaderService(),
                    self::getWriter(),
                    self::getReader()
            );
        }
        
        return self::$mainService;
    }

    public static function getAllowedAuthorService(): GetAllowedAuthorsService
    {
        if(!self::$getAuthorsService){
            self::$getAuthorsService = new GetAllowedAuthorsService(RepositoryFabric::getAuthorRepository());
        }
        
        return self::$getAuthorsService;
    }
    
    public static function getAllowedBooksService(): GetAllowedBooksService
    {
        if(!self::$getBooksService){
            self::$getBooksService = new GetAllowedBooksService(RepositoryFabric::getBookRepository());
        }
        
        return self::$getBooksService;
    }
    
    public static function getAllowedReaderService(): GetAllowedReaderService
    {
        if(!self::$getReaderService){
            self::$getReaderService = new GetAllowedReaderService(RepositoryFabric::getReaderRepository());
        }
        
        return self::$getReaderService;
    }
    
    public static function getJanreService(): GetAllowedJanreService
    {
        if(!self::$getJanreService){
            self::$getJanreService = new GetAllowedJanreService();
        }
        
        return self::$getJanreService;
    }
    
    public static function getCountryService(): GetAllowedCountryService
    {
        if(!self::$getCountryService){
            self::$getCountryService = new GetAllowedCountryService();
        }
        
        return self::$getCountryService;
    }
    
    public static function getLanguageService(): GetAllowedLanguageService
    {
        if(!self::$getLanguageService){
            self::$getLanguageService = new GetAllowedLanguageService();
        }
        
        return self::$getLanguageService;
    }
}
