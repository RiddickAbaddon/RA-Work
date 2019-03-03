<?php
    $this->add_controller('help');
    $this->add_js('inc/smoth-scrollbar/smooth-scrollbar.js');
    $this->add_js('inc/smoth-scrollbar/plugins/overscroll.js');
    $this->add_js('js/scrollbar.js');
    $this->add_js('js/help.js');
    $this->add_css('inc/fontello/css/fontello.css');
?>
<div class="body">
    <div id="top"></div>
    <div class="container">
        <div class="title-bar">
            Pomoc
        </div>
        <p>
            Witaj w aplikacji <?= $this->project_name ?>. Jest to aplikacja do zarządzania projektami w twojej firmie. W zależności od przydzielonych Ci uprawnień możesz przeglądać projekty, zarządzać nimi, zarządzać użytkownikami oraz grupami, przydzielać ludzi do projektów a projekty przydzielać do grup w celu zapewnienia dyskrecji. Poniżej znajduje się instrukcja jak z tych wszystkich funkcji korzystać. W każdym momencie możesz wrócić tutaj klikając ikonkę znaku zapytania w prawym panelu w dolnej sekcji.
        </p>

        <div class="title-id" id="spis-tresci">
            <div>Spis treści<a class="hash" href="#spis-tresci">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <ol class="list-navigation">
            <li><a href="#nawigacja">Nawigacja</a></li>
            <li><a href="#wyszukiwanie-projektow">Wyszukiwanie projektów</a></li>
            <li><a href="#widok-projektu">Widok projektu</a></li>
            <li><a href="#udostepnianie-projektu">Udostępnianie projektu</a></li>
            <li><a href="#dodawanie-projektu">Dodawanie projektu</a></li>
            <li><a href="#zalaczniki">Załączniki</a></li>
            <li><a href="#widocznosc-projektu">Widoczność projektu</a></li>
            <li><a href="#edycja-projektu">Edycja projektu</a></li>
            <li><a href="#konczenie-projektu">Kończenie projektu</a></li>
            <li><a href="#usuwanie-projektu">Usuwanie projektu</a></li>
            <li><a href="#priorytet-projektu">Priorytet projektu</a></li>
            <li><a href="#profil-uzytkownika">Profil użytkownika</a></li>
            <li><a href="#uprawnienia">Uprawnienia</a></li>
            <li><a href="#edycja-profilu">Edycja profilu</a></li>
            <li><a href="#dodawanie-nowego-uzytkownika">Dodawanie nowego użytkownika</a></li>
            <li><a href="#zarzadzanie-uzytownikami">Zarządzanie użytkownikami</a></li>
            <li><a href="#grupy-uzytkownikow">Grupy użytkowników</a></li>
            <li><a href="#w-razie-problemow">W razie problemów</a></li>
        </ol>


        <div class="title-id" id="nawigacja">
            <div>Nawigacja<a class="hash" href="#nawigacja">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <figure>
            <a href="img/help/przestrzeń robocza.png" target="_blank">
                <img src="img/help/przestrzeń robocza.png">
            </a>
            <figcaption>
                Przestrzeń robocza
            </figcaption>
        </figure>
        <figure>
            <a href="img/help/przestrzeń robocza mobile.png" target="_blank">
                <img src="img/help/przestrzeń robocza mobile.png">
            </a>
            <figcaption>
                Na smartfonie układ jest taki sam z tą różnicą, że lewy i prawy panel są schowane po bokach
            </figcaption>
        </figure>

        <div class="title-id" id="wyszukiwanie-projektow">
            <div>Wyszukiwanie projektów<a class="hash" href="#wyszukiwanie-projektow">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
        Projekty można wyszukiwać na wiele sposobów. Można wpisać szukaną frazę w polu wyszukiwania. W ten sposób wyszukijesz po nazwie projektu lub nazwie klienta. Nie trzeba potwierdzać wyszukiwanej frazy żadnym przyciskiem, wyszukiwanie odbywa się w czasie rzeczywistym. Pamiętaj, że przy sortowaniu jest strzałka po kliknięciu której zmienia się kierunek sortowania.
        </p>
        <figure>
            <a href="img/help/filtrowanie.png" target="_blank">
                <img src="img/help/filtrowanie.png">
            </a>
            <figcaption>
                Aby użyć filtra lub sortowania należy rozwinąć listę
            </figcaption>
        </figure>

        <div class="title-id" id="widok-projektu">
            <div>Widok projektu<a class="hash" href="#widok-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <figure>
            <a href="img/help/widok projektu.png" target="_blank">
                <img src="img/help/widok projektu.png">
            </a>
            <figcaption>
                Przykładowy widok projektu
            </figcaption>
        </figure>
        <figure>
            <a href="img/help/Opcje zarządzania projektem.png" target="_blank">
                <img src="img/help/Opcje zarządzania projektem.png">
            </a>
            <figcaption>
                Po prawej stronie pojawią się opcje do zarządzania projektem. Opcje są dostosowane do statusu projektu oraz wybranej akcji
            </figcaption>
        </figure>

        <div class="title-id" id="udostepnianie-projektu">
            <div>Udostępnianie projektu<a class="hash" href="#udostepnianie-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Aby przesłać szybko komuś projekt wystarczy skopiować do niego link. W tym celu należy wejść w projekt i w prawym panelu kliknąć: <i class="icon-export"></i>. Ta opcja skopiuje link do projektu, który będzie można wysłać. Jeśli z jakichś powodów kopiowanie linku się nie powiedzie zostanie on wyświetlony w okienku popup. 
        </p>
        <p>
            Projekt wywołany takim linkiem wyswietli się bez panelu ale jest dostępny tylko dla zalogowanych użytkowników. Niezalogowany odbiorca zobaczy jedynie link do logowania. Więcej o widoczności projektów znajdziesz w rozdziale: <a class="link" href="#widocznosc-projektu">widoczność projektów</a>.
        </p>

        <div class="title-id" id="dodawanie-projektu">
            <div>Dodawanie projektu<a class="hash" href="#dodawanie-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Aby dodać projekt należy wybrać w prawym panelu opcje: <i class="icon-doc-add"></i>. Trzy pierwsze pola tekstowe muszą być uzupełnione a wrtość w każdym z nich nie może być mniejsza niż 3 znaki aby projekt mógł być dodany. Nazwa projektu musi być unikalna.
        </p>
        <p>
            W każdej chwili możesz przerwać edycje projektu klikając opcję <i class="icon-cancel"></i> w prawym panelu. Pamiętaj jednak, że wprowadzonych zmian nie da się wtedy odzyskać.
        </p>
        <p>
            Aby przydzielić projekt do użytkownika należy zaznaczyć jego nick w ostatniej liście wyboru. Możeszy przydzielić projekt do wielu użytkowników. Możesz również przydzielić projekt do jednej grupy. Więcej informacji w rozdziale: <a class="link" href="#widocznosc-projektu">widoczność projektów</a>.
        </p>
        <p>
            Informacje o dodawaniu załączników znajdziesz tutaj: <a class="link" href="#zalaczniki">załączniki</a>
        </p>
        
        <div class="title-id" id="zalaczniki">
            <div>Załączniki<a class="hash" href="#zalaczniki">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Aby dodać załącznik należy kliknąć w ikonę <i class="icon-doc-add"></i> w sekcji załączniki. Wysunie się menu dodawania załącznika. Należy wpisać nazwę załącznika, które będzie się wyświetlała w widoku projektu (musi być unikalna dla reszty załączników w projekcie oraz zawierać minimum 3 znaki). Następnie jeśli chcesz dodać załącznik z linkiem po prostu go wpisz w poniższym polu lub jeśli chcesz przesłać plik, wybierz go za pomocą odpowiedniej opcji. Nie zaleca się przesyłania na serwer dużych plików z uwagi na oszczędność miejsca na serwerze. Do dużych plików lepiej jest podać link z udostępnionymi plikami z dysku w chmurze. Po podaniu wszystkich informacji należy zatwierdzić załącznik przyciskie "Zapisz".
        </p>
        <figure>
            <a href="img/help/załączniki.png" target="_blank">
                <img src="img/help/załączniki.png">
            </a>
            <figcaption>
                Dodawanie załącznika do projektu
            </figcaption>
        </figure>
        <p>
            Aby usunąć załącznik należy po prostu kliknąć ikonkę <i class="icon-cancel"></i> obok nazwy załącznika.
        </p>
        <p>
            Jeśli chcesz się dowiedzieć jak będzie wyglądał i działał dodany załącznik zajrzyj do rozdziału: <a class="link" href="#widok-projektu">widok projektu</a>
        </p>

        <div class="title-id" id="widocznosc-projektu">
            <div>Widoczność projektu<a class="hash" href="#widocznosc-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Projekty nie przydzielone do żadnej grupy użytkowników są widoczne dla każdego użytkownika. Jeśli przydzielisz projekt do którejś grupy tylko członkowie danej grupy będą mogli wyświetlić ten projekt. Nie zobaczysz go też na liście projektów.
        </p>

        <div class="title-id" id="edycja-projektu">
            <div>Edycja projektu<a class="hash" href="#edycja-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Edycja projektu wygląda identycznie jak jego tworzenie. Więcej informacji znajdziesz w rozdziale: <a class="link" href="#dodawanie-projektu">dodawanie projektu</a>
        </p>

        <div class="title-id" id="konczenie-projektu">
            <div>Kończenie projektu<a class="hash" href="#konczenie-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Kiedy prace nad projektem dobiegną końca można go oznaczyć jako zakończony. W tym celu należy wybrać z panelu opcję <i class="icon-check"></i>. Aby zobaczyć ukończone projekty należy wybrać filtr "Wszystkie" lub "Ukończone".
        </p>
        <p>
            Jeśli zdecydujesz się zakończyć projekt nie będziesz go mógł już edytować.
        </p>


        <div class="title-id" id="usuwanie-projektu">
            <div>Usuwanie projektu<a class="hash" href="#usuwanie-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Aby usunąć projekt należy wybrać opcję <i class="icon-trash-empty"></i> z prawego panelu. Podczas usuwania projektu usuwane są wszystkie informacje o projekcie, powiązania z użytkownikami oraz grupami a także wszystkie pliki przesłane na serwer jako załączniki do tego projektu. Po usunięciu projektu nie da się odzyskać tych danych dlatego bądź ostrożny w każdystaniu z tej opcji.
        </p>

        <div class="title-id" id="priorytet-projektu">
            <div>Priorytet projektu<a class="hash" href="#priorytet-projektu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Każdy projekt ma przydzielony priorytet. Domyślnie jest ustawiony jako "zwykły". Jeśli jakiś projekt wymaga szczególnej uwagi lub zostało mało czasu na jego zrealizowanie można podnieść jego priorytet (więcej w rozdziale: <a class="link" href="#edycja-projektu">edycja projektu</a>). Ma to charakter jedynie informacyjny, wszędzie gdzie jest widoczny projekt jest on zaakcentowany kolorem ustawionego priorytetu.
        </p>
        <figure>
            <a href="img/help/Zaakcentowany projekt.png" target="_blank">
                <img src="img/help/Zaakcentowany projekt.png">
            </a>
            <figcaption>
                Projekty o wyższym priorytecie są zaakentowane kolorem ustawionego priorytetu
            </figcaption>
        </figure>

        <div class="title-id" id="profil-uzytkownika">
            <div>Profil użytkownika<a class="hash" href="#profil-uzytkownika">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <figure>
            <a href="img/help/Profil.png" target="_blank">
                <img src="img/help/Profil.png">
            </a>
            <figcaption>
                Możesz z tego poziomu edytować swój profil lub usunąć konto. Tylko właściciel konta ma dostęp do ustawień profilu.
            </figcaption>
        </figure>
        <p>
            W zalezności od typu konta masz dostęp do różnego zestawu funkcji. Więcej o typach kont oraz przysługujących im funkcjach znajdziesz w rozdziale: <a class="link" href="#uprawnienia">uprawnienia</a>
        </p>

        <div class="title-id" id="uprawnienia">
            <div>Uprawnienia<a class="hash" href="#uprawnienia">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            W systemie dostępne są obecnie 3 rodzaje kont. Każdy typ konta ma określone uprawnienia, które pozwalają na dostęp do określonych funkcji. Poniżej znajduje się lista typów kont oraz ich uprawnień:
        </p>
        <h5>Czytelnik</h5>
        <p>
            Jest to podstawowe konto, które pozwala jedynie na wyświetlanie projektów.
        </p>
        <p>
            Ten typ konta nie posiada zadnych dodatkowych uprawnień.
        </p>
        <h5>Project manager</h5>
        <p>
            Ten typ konta umożliwia zarządzanie projektami oraz przydzielanie ich do użytkowników. Posiada też opcję dodawania konta ale jedynie jako czytelnika.
        </p>
        <p>
            Uprawnienia:
        </p>
        <ul>
            <li>Zarządzanie projektami</li>
            <li>Dodanie czytelnika</li>
            <li>Przydzielanie do projektów</li>
        </ul>
        <h5>Administrator</h5>
        <p>
            Ten typ konta umożliwia zarządzanie projektami oraz przydzielanie ich do użytkowników. Posiada również uprawnienia do zarządzania, użytkownikami oraz grupami.
        </p>
        <p>
            Uprawnienia:
        </p>
        <ul>
            <li>Zarządzanie projektami</li>
            <li>Dodanie czytelnika</li>
            <li>Przydzielanie do projektów</li>
            <li>Zarządzanie użytkownikami</li>
            <li>Zarządzanie grupami</li>
        </ul>

        <div class="title-id" id="edycja-profilu">
            <div>Edycja profilu<a class="hash" href="#edycja-profilu">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Aby edytować profil należy kliknąć opcję <i class="icon-user-circle-o"></i> w prawym panelu. Następnie wybrać opcje "Edytuj". Wystarczy zmienić interesującą Cię informacje i zatwierdzić zmiany.
        </p>
        <p>
            Wytyczne dotyczące ustawiania hasła są następujące: Nowe hasło musi zawierać conajmniej 8 znaków, przynajmniej jedną małą i jedną dużą literę, jedną cyfrę i jeden znak specjalny.
        </p>

        <div class="title-id" id="dodawanie-nowego-uzytkownika">
            <div>Dodawanie nowego użytkownika<a class="hash" href="#dodawanie-nowego-uzytkownika">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            <?= $this->project_name ?> to aplikacja zamknięta dla osób postronnych dlatego aby się zarejestrować musisz zostać zaproszony przez kogoś z wewnątrz. Aby dodać użytkownika należy kliknąć opcję <i class="icon-users"></i> w prawym panelu. W zależności Od twoich uprawnień widok będzie się różnił (Czytelnicy nie mają dostępu do panelu zarządzania użytkownikami).
        </p>
        <p>
            Jesli jesteś project managerem zobaczysz jedno pole do dodawania użytkownika. Dodany w teki sposób użytkownik może mieć jedynie uprawnienia czytelnika. Jeśli jesteś administratorem należy kliknąć na liście użytkowników ikonkę <i class="icon-user-plus"></i>. Otworzy się panel dodawania użytkownika. W przeciwieństwie do widoku project managera, administrator może wybrać typ konta dla nowego użytkownika.
        </p>
        <p>
            Następnie należy wpisać adres e-mail nowego użytkownika i zatwierdzić przyciskiem "Dodaj". Nowy użytkownik dostanie maila z linkiem do strony gdzie będzie mógł się zarejestrować.
        </p>
        <div class="row">
            <div class="col-50">
                <figure>
                    <a href="img/help/dodawanie użytkownika 1.png" target="_blank">
                        <img src="img/help/dodawanie użytkownika 1.png">
                    </a>
                    <figcaption>
                        Dodawanie użytkownika z perspektywy project managera
                    </figcaption>
                </figure>
            </div>
            <div class="col-50">
                <figure>
                    <a href="img/help/dodawanie użytkownika 2.png" target="_blank">
                        <img src="img/help/dodawanie użytkownika 2.png">
                    </a>
                    <figcaption>
                        Dodawanie użytkownika z perspektywy administratora
                    </figcaption>
                </figure>
            </div>
        </div>

        <div class="title-id" id="zarzadzanie-uzytownikami">
            <div>Zarządzanie użytkownikami<a class="hash" href="#zarzadzanie-uzytownikami">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Panel zarządzania użytkownikami zawiera zarówno opcje do zarządzania użytkownikami jak i grupami użytkowników. Więcej o grupach dowiesz się w rozdziale: <a class="link" href="#grupy-uzytkownikow">grupy użytkowników</a>.
        </p>
        <p>
            W sekcji "Użytkownicy" pojawi się lista użytkowników. Kliknięcie w nazwę spowoduje przejście do profilu danego użytkownika. Obok jest ikona <i class="icon-edit"></i>, po kliknięciu której rozwinie się panel edycji danego użytkownika. Panel ten umożliwia zmianę uprawnień danego konta, usunięcie konta oraz zablokowanie konta. Użytkownik, który jest zablokowany nie będzie w stanie się zalogować. Nie możesz z tego miejsca edytować swojego własnego konta.
        </p>
        <figure>
            <a href="img/help/Edycja profilu innego użytkownika.png" target="_blank">
                <img src="img/help/Edycja profilu innego użytkownika.png">
            </a>
            <figcaption>
                Panel edycji użytkownika
            </figcaption>
        </figure>
        <p>
            Ten schemat obowiązuje również przy edycji grup. Aby dodać lub usunąć użytkownika z grupy należy zaznaczyć lub odznaczyć go na liście w edycji grupy.
        </p>

        <div class="title-id" id="grupy-uzytkownikow">
            <div>Grupy użytkowników<a class="hash" href="#grupy-uzytkownikow">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Grupy użytkowników służą do wydzielenia pewnych projektów tylko dla konkretnej grupy użytkowników. Jeśli przydzielisz projekt do grupy użytkowników, widzieć go będą mogli jedynie członkowie danej grupy. Więcej o widoczności projektów znajdziesz w rozdziale: <a class="link" href="#widocznosc-projektu">widoczność projektu</a>.
        </p>
        <p>
            Grupy oraz zarządzanie nimi odbywa się identycznie jak w przypadku edycji użytkowników. Więcej na ten temat znajdziesz w rozdziale: <a class="link" href="#zarzadzanie-uzytkownikami">zarządzanie użytkownikami</a>
        </p>
        
        <figure>
            <a href="img/help/Edycja grupy.png" target="_blank">
                <img src="img/help/Edycja grupy.png">
            </a>
            <figcaption>
                Członków grupy dodaje się i usuwa zaznaczając ich w liście wszystkich użytkowników w otwartym panelu edycji użytkowników i zatwierdzając zmiany przyciskiem "Zapisz".
            </figcaption>
        </figure>

        <div class="title-id" id="w-razie-problemow">
            <div>W razie problemów<a class="hash" href="#w-razie-problemow">#</a></div>
            <a href="#top" class="top"><i class="icon-up-open"></i> Do góry</a>
        </div>
        <p>
            Jeśli jakaś funkcja nie działa prawidłowo, możesz to zgłosić do twórcy wysyłając wiadomość e-mail na adres: <a class="link" href="mailto: marcin36k@outlook.com">marcin36k@outlook.com</a>
        </p>


        <div style="height: 50px"></div>
        <hr>
        <p>
            <?= $this->project_name ?><br/>
            <?= $this->footer_text ?><br/>
            <?= $this->footer2_text ?><br/>
        </p>
    </div>
</div>