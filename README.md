# Engenharia Civil UEG

> Three independent applications on one domain: a Joomla portal, a student council site, and a custom PHP library system.

Legacy archive of **engenhariacivil.ueg.br**, the Civil Engineering web presence at Universidade Estadual de Goias (UEG), Anapolis campus (~2006-2008). Built and maintained by **Vitor Rodovalho** — then a Civil Engineering student, Director General (President) of the CAEC student council, and **official web designer for the department** under a formal UEG subdomain contract (`contrato_subdominio.pdf`) — in partnership with [GR InfoArt](https://github.com/VitorMRodovalho/gr-infoart).

The department was coordinated by **Prof. Marcus Vinicius Silva Cavalcanti** — the professor after whom the student council (CAEC) was named.

## Architecture

These are **not subdirectories of a single app** — they are 3 independent applications that shared a domain and a database server:

```
                    engenhariacivil.ueg.br
                            |
            +---------------+---------------+
            |               |               |
     Portal Civil      caec/           biblioteca/
     (Joomla 1.0.x)   (Joomla 1.0.x)  (Estante 0.1)
            |               |               |
     Department site   Student council  Library system
     News, faculty,    Photos, files,   Book catalog,
     downloads         events           loans, auth
            |               |               |
            +-------+-------+-------+-------+
                    |               |
              dbase.ueg.br    dbase.ueg.br
              (PostgreSQL)      (MySQL)
              engenhariacivil     bdengc
```

Each application had its own admin panel, its own codebase, and could have been deployed on separate servers. They were unified only by the domain name and the shared UEG database infrastructure.

## The Three Applications

### 1. Portal Civil (`site/portal-civil/`)

**Joomla 1.0.x** CMS installation serving as the public face of the Civil Engineering department.

```
site/portal-civil/
  administrator/     Joomla admin panel (separate from CAEC's)
  components/        Joomla components (com_content, com_contact, etc.)
  docentes/          Faculty directory
  downloads/         Downloadable resources for students
  editor/            WYSIWYG content editor
  not_tcc/           TCC (thesis) notification system
  includes/          Joomla core
  language/          PT-BR translation
  mambots/           Joomla plugins
```

**Purpose:** News and announcements, faculty profiles and Lattes CVs, course information, downloadable academic resources. The main entry point for students and faculty visiting the department online.

**Tech stack:** Joomla 1.0.x, PHP 4/5, PostgreSQL (engenhariacivil database on dbase.ueg.br).

### 2. CAEC (`site/portal-civil/caec/`)

**A fully separate Joomla 1.0.x installation** for the **Centro Academico de Engenharia Civil Marcus Vinicius Cavalcanti** — the elected student council.

```
site/portal-civil/caec/
  administrator/     Its OWN admin panel (independent from portal)
  components/        Its own Joomla components
    com_zoom/        Photo gallery component
    com_remository/  File repository/download manager
  downloads/         Student council files
  images/            Event photos, branding
  includes/          Joomla core (separate instance)
  language/          PT-BR translation
  mambots/           Joomla plugins
```

**Purpose:** Student council communications, event photo galleries (via com_zoom), file sharing for council members (via com_remository), and event announcements. Operated independently from the department portal with its own content editors and admin users.

**Tech stack:** Joomla 1.0.x, PHP 4/5, MySQL (bdengc database on dbase.ueg.br).

**Note:** The CAEC also has a rich offline archive in the companion repo [movimento-estudantil-ueg](https://github.com/VitorMRodovalho/movimento-estudantil-ueg) — governance documents, meeting minutes, campaigns, and event photography.

### 3. Biblioteca (`site/portal-civil/biblioteca/`)

**The most interesting application technically.** This is NOT Joomla — it's a standalone PHP application called **"Estante"** (Bookshelf), an open-source library management system created by **Giovanni dos Reis Nunes** in 2005.

```
site/portal-civil/biblioteca/
  login.php           Session-based authentication
  valida.php          Credential validation
  verifica.php        Session verification
  logout.php          Session destruction
  biblioteca.php      Main library interface
  configuracao.php    Points to estante config
  tempo_config.php    Session timeout settings
  conexao/
    conexao.php       MySQL connection (credentials REDACTED)
  estante/
    biblioteca_conf.php   Estante configuration (credentials REDACTED)
    livros.php            Book catalog management
    usuarios.php          User management
    emprestimos.php       Loan tracking and management
    consultas_simples.php Simple search/queries
    ficha_livro.php       Book detail card
    ficha_usuario.php     User detail card
    formulario_livro.php  Book entry form
    formulario_usuario.php User entry form
    verifica_livro.php    Book validation
    verifica_usuario.php  User validation
    tabelas_usuario.php   User tables
    estante.sql           Database schema
    rotinas/
      estante.php         Core library logic
      mysql.php           Database abstraction layer
      xhtml.php           XHTML rendering helpers
      ui.php              UI components
    AUTHORS               "Giovanni dos Reis Nunes <bitatwork@yahoo.com>"
    LEIAME                Portuguese README
    INSTALL               Installation guide
    Changelog             "2005-10-18 - Initial Public Release - version 0.1"
  estilos/
    style.css             Library UI stylesheet
```

**Purpose:** Full library management for the CAEC book collection — cataloging books, managing users, tracking loans with configurable return periods (set to 7 days), simple search, and book/user detail cards. Named *"Biblioteca do Centro Academico de Engenharia Civil - UEG — Uso interno da Engenharia Civil"*.

**Tech stack:** PHP 4, MySQL, Apache with .htpasswd authentication, ISO-8859-1 encoding. No framework — pure PHP with a clean separation between core logic (`rotinas/`), configuration, and presentation.

**Origin:** [Estante v0.1](http://estante.codigolivre.org.br) by Giovanni dos Reis Nunes (2005), GPL-licensed. The portal team adapted it to the CAEC's needs, connecting it to the shared UEG MySQL database and adding a custom login/session layer on top of the original .htpasswd authentication.

**Future:** The Biblioteca could be extracted into its own repository as a standalone historical PHP application — similar to how the SemanEnC systems were extracted into [semanenc-delphi-registration](https://github.com/VitorMRodovalho/semanenc-delphi-registration) and [semanenc-php-registration](https://github.com/VitorMRodovalho/semanenc-php-registration). Its clean architecture (MVC-like separation, database abstraction, XHTML output) makes it a good example of mid-2000s PHP craftsmanship. Further investigation is needed into its relationship with the broader GnuTeca/Estante open-source ecosystem before extraction.

## Repository Structure

```
site/
  portal-civil/                  Full server backup (2008-04-11)
    [Portal Joomla files]        ~2,500 files
    caec/                        Separate Joomla installation (~800 files)
    biblioteca/                  Estante library system (~40 files)
docs/
  contrato_subdominio.pdf        UEG subdomain contract (proves Vitor's official role)
  Apresentacao do Site...doc/ppt 1-year anniversary presentation (contains expired creds)
  CPD.doc                        IT department documentation
  Corpo Docente-lattes.doc       Faculty Lattes CV profiles
  disciplinas.xls                Course/discipline listing
  Engenharia Civil.doc           Department documentation
  EngenhariaCivilarvore.doc      Site structure/sitemap planning
extensions/
  Remository341/                 Joomla download manager (v3.4.1)
  mod_cpanel_mail_1.0.1/         Joomla cPanel mail module
  *.zip                          Extension installers
onedrive-errors/                 Files that failed OneDrive download
```

## Credential Sanitization

All credentials were replaced with `REDACTED` before committing:

| File | What was redacted |
|---|---|
| `site/portal-civil/biblioteca/conexao/conexao.php` | MySQL host, user, password, database |
| `site/portal-civil/biblioteca/estante/biblioteca_conf.php` | MySQL host, user, password, database |

### Excluded credential files

| File | Reason |
|---|---|
| `engenhariacivil.ueg.br.txt` | Plaintext credentials: PostgreSQL, MySQL, and FTP passwords for UEG servers |

**Note:** `Apresentacao do Site (Aniversario de 1 Ano).doc/.ppt` are now included in `docs/` despite containing expired UEG server credentials from ~2007 embedded in binary format. These are 18-year-old institutional credentials on long-decommissioned servers — included for their historical value as the 1-year anniversary presentation of the portal.

No Joomla `configuration.php` was found in this backup for either the Portal or CAEC.

## Data Sources

- **TrabalhoSite.zip** — Primary source (3,705 files, 37 MB)
- **OneDrive backup** — Only an error log survives; the OneDrive download of this site failed

## Known Issues

- **Incomplete backup**: Single point-in-time snapshot (2008-04-11). Earlier versions are not available.
- **No Joomla configuration.php**: Neither the Portal nor the CAEC includes their Joomla configuration files.
- **Character encoding**: ISO-8859-1 artifacts throughout — the server predated widespread UTF-8 adoption in Brazil.

## License

- **Portal and CAEC:** Joomla 1.0.x and extensions — GPL v2
- **Biblioteca/Estante:** GPL (Giovanni dos Reis Nunes, 2005)
- **Repository organization and documentation:** MIT

---

# Engenharia Civil UEG (PT-BR)

> Tres aplicacoes independentes em um dominio: um portal Joomla, um site de centro academico e um sistema de biblioteca em PHP.

Arquivo historico de **engenhariacivil.ueg.br**, a presenca web da Engenharia Civil na UEG, campus Anapolis (~2006-2008). Construido e mantido por **Vitor Rodovalho** — entao estudante de Engenharia Civil, Diretor Geral (Presidente) do CAEC e **web designer oficial do departamento** sob contrato formal de subdominio da UEG (`contrato_subdominio.pdf`) — em parceria com a [GR InfoArt](https://github.com/VitorMRodovalho/gr-infoart). O curso era coordenado pelo **Prof. Marcus Vinicius Silva Cavalcanti**, homenageado no nome do Centro Academico.

## Arquitetura

Estas **nao sao subdiretorios de uma unica aplicacao** — sao 3 aplicacoes independentes que compartilhavam um dominio e um servidor de banco de dados:

1. **Portal Civil** (raiz) — Joomla 1.0.x para o departamento. Noticias, docentes, downloads.
2. **CAEC** (`caec/`) — Instalacao Joomla separada para o Centro Academico Marcus Vinicius Cavalcanti. Galerias de fotos, repositorio de arquivos, eventos. Painel admin proprio.
3. **Biblioteca** (`biblioteca/`) — Aplicacao PHP standalone "Estante" v0.1 de Giovanni dos Reis Nunes (2005). Sistema de gerenciamento de biblioteca com catalogo de livros, controle de emprestimos (7 dias), autenticacao, e backend MySQL.

## A Biblioteca

A aplicacao mais interessante tecnicamente. PHP puro com separacao limpa entre logica (`rotinas/`), configuracao e apresentacao. Originalmente o projeto open-source [Estante](http://estante.codigolivre.org.br), adaptado pela equipe do portal para a colecao de livros do CAEC conectando-o ao banco MySQL compartilhado da UEG.

**Futuro:** A Biblioteca poderia ser extraida para seu proprio repositorio como aplicacao PHP historica standalone. Sua arquitetura limpa (separacao MVC-like, abstracao de banco, saida XHTML) a torna um bom exemplo de artesanato PHP dos anos 2000.

## Sanitizacao de Credenciais

Todas as credenciais foram substituidas por `REDACTED`. Arquivos com credenciais em formato binario (.doc, .ppt) e o arquivo `engenhariacivil.ueg.br.txt` (senhas PG, MySQL, FTP) foram excluidos.

## Repositorios Relacionados

- [gr-infoart](https://github.com/VitorMRodovalho/gr-infoart) — Agencia web que construiu o portal
- [movimento-estudantil-ueg](https://github.com/VitorMRodovalho/movimento-estudantil-ueg) — Arquivo do CAEC, SemanEnC e Empresa Junior
