# EN
> Live Stats for OBS Studio

This application allows you to display live statistics for OBS Studio using the YouTube API v3. It is easy to configure by simply changing the API key in the `config.php` file.

### Setup Instructions:

1. **Get your YouTube v3 API Key**:
   To obtain your API key, go to [Google Cloud Console](https://console.developers.google.com/), sign up, and create a project. Then enable the YouTube Data API v3 and generate your API key.

2. **Change the API Key**:
   Open the `config.php` file and change the value of `API_KEY` to your generated API key from Google Cloud.
   
3. **Edit config.php**
```php
<?php
$API_KEY = 'insert_your_api_key_here';
?>
```
4. **Upload the index.php and config.php files to a hosting that supports php**
5. **Final effect**   
![preview](https://i.imgur.com/X2cVxVT.png)
6. **Ready**


# PL
> Statystyki Live dla OBS Studio 

Aplikacja umożliwia wyświetlanie statystyk na żywo dla OBS Studio, wykorzystując API YouTube v3. Można ją łatwo skonfigurować, zmieniając tylko klucz API w pliku `config.php`.

### Instrukcja konfiguracji 

1. **Pobierz klucz API YouTube v3**:
   Aby uzyskać klucz API, przejdź do [Google Cloud Console](https://console.developers.google.com/), zarejestruj się i stwórz projekt. Następnie włącz YouTube Data API v3 i wygeneruj swój klucz API.

2. **Zmień klucz API**:
   Otwórz plik `config.php` i zmień wartość `API_KEY` na swój klucz API z Google Cloud.
   
3. **Edytuj plik config.php**
```php
<?php
$API_KEY = 'twój klucz api';
?>
```
4. **Wgraj pliki index.php i config.php na hosting który obsuguje php**
5. **Efekt końcowy**
![preview](https://i.imgur.com/fLapP2Q.png)
6. **Gotowe**

