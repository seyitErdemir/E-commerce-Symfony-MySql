# E-commerce-Symfony-MySql


Kullanılan Teknolojiler ve Sürümleri ( Technologies Used and Versions )


    -> Php 8.0.6
    -> Composer 2.3.8
    -> Symfony 5.4
    -> MySql 5.7.31 
    

Sistemi çalışır hale getirmek için indirdiğimiz dosya klasörümüzü ide ya da text editör aracılığıyla açıyoruz. Daha sonrasında  Composer aracılığıyla kullanmamız gereken paketleri indiriyoruz.

( To make the system work, we open our downloaded file folder via ide or text editor. Then we download the packages we need to use through Composer.)

    -> composer install
    
Bu işlem sonunda klasörümüze vendor ve var klasörleri eklenmekte. Daha sonrasında veritabanı işlemlerimiz için sunucumuzu çalıştırıyoruz. Veritabanı işlemlerimiz için  .env  sayfamızda yer alan DATABASE_URL  değişkenimizi kendinize uygun şekilde güncelleyiniz. Sonrasında migrations klasöründe yer alan ve güncel model bilgilerimiz  ve veritabanı oluşturmak için terminalimize alttaki komutları giriyoruz. 

( At the end of this process, the vendor and var folders are added to our folder. Then we run our server for our database operations. For our database operations, please update our DATABASE_URL variable on our .env page to suit you. Then we enter the commands in our terminal to create our current model information and database in the migrations folder. )

    -> php bin/console doctrine:database:create
    -> php bin/console make:migration
    -> php bin/console doctrine:migrations:migrate
    
Sistem için gerekli veritabanı  ve tabloları oluşturuldu. ( The necessary database and tables for the system were created. )

    -> symfony server:start 
  
Yukarıdaki komutu kullanarak projeyi çalıştırıyoruz ve her adımı doğru uyguladıysak https://127.0.0.1:8000/  adresine  logine gidiyoruz. Panele giriş için default olarak admin rollü bir kullanıcı oluşmakta ve bilgileri : 

We run the project using the above command and if we have followed each step correctly, we go to https://127.0.0.1:8000/ to login. By default, a user with admin role is created to login to the panel and its information is:
  
    -> admin@gmail.com  123456 
    
Şuanda sistemimiz çalışmaktadır. Bundan sonrasında panel üzerinden  kategori , ürün ve kullanıcı ekleyebilir , silebilir , güncelleyebilirsiniz.  

( Our system is currently working. After that, you can add, delete, update categories, products and users on the panel. )

Ürün listelendiği index sayfası.( The index page where the product is listed. )

    -> https://127.0.0.1:8000/page    
     
 Allmaya çalışılan ürünlerin listelendiği sayfa. ( The page where the products that are tried to be bought are listed. )    
 
     -> https://127.0.0.1:8000/basket
     
Geçmiş alışverişlerin listelendiği sayfa ( Page listing past purchases )  

     -> https://127.0.0.1:8000/tracking

Sepetinizde ürün varsa alışveriş tamamlama sayfası ( Checkout page if you have items in your cart )

    -> https://127.0.0.1:8000/check-out
    
    
Örnek veri girişi için veritabanımızı silip tekrar create komutuyla kurduktan sonra .DB klasörümüzde yer alan  db_symfony2.sql sayfasını içe aktar yaparak kullanabilirsiniz.

( For sample data entry, after deleting our database and installing it again with the create command, you can use the db_symfony2.sql page in our .DB folder by importing it. )
    
    

