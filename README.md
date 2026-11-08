# Bill of Materials (BoM)

A [Laravel](https://laravel.com) + [Filament](https://filamentphp.com) application for managing
**Software Bills of Materials (SBOM), Quantum Bills of Materials (QBOM), Cyber Security Bills of
Materials (CBOM), AI Bills of Materials (AIBOM) and Hardware Bills of Materials (HBOM)** in line with the
**CERT-In Technical Guidelines on SBOM, QBOM & CBOM, AIBOM and HBOM Version 2.0** dated **09.07.2025**.

The application lets an organisation capture the **levels** and **classification** of every SBOM
(Top-level / n-level / Delivery / Transitive / Complete; Design / Source / Build / Analyzed / Deployed /
Runtime), collect the **minimum elements** required by CERT-In for each BoM type (SBOM Table 5, QBOM/CBOM
Tables 8 & 9, AIBOM Table 10, HBOM Table 11) and export every BoM as a multi-sheet **XLSX** workbook.

## Features

- **BoM management** for the five BoM types (`sbom`, `qbom`, `cbom`, `aibom`, `hbom`) with product
  linkage (Laravel `products` table).
- **SBOM levels** — Top-level, n-level, Delivery, Transitive, Complete (required for SBOMs).
- **SBOM classification** — Design, Source, Build, Analyzed, Deployed, Runtime (SDLC stage based).
- **Minimum-element data entry** per BoM type:
  - SBOM components: name, version, description, supplier, license, origin, dependencies, vulnerabilities,
    patch status, release / end-of-life dates, criticality, usage restrictions, checksums/hashes, author,
    executable / archive / structured properties, unique identifier (purl / CPE).
  - QBOM / CBOM: model / asset names, algorithms, primitives, modes, crypto functions, security levels, OIDs,
    keys, protocols, certificates, hardware, dependencies, environment, attestations, vulnerabilities.
  - AIBOM: model name/version, type, developer, licensing, software dependencies, ML models & algorithms,
    performance metrics, data source / datasets, hardware, security requirements, input / output, intended and
    out-of-scope usage, environmental impact, vulnerabilities, attestations.
  - HBOM: product name/version/details, warranty / AMC, manufacturer, supplier, serial numbers, technical specs,
    technology node, compliance, power supply, license, test result and recursive sub-components.
- **XLSX export** — one workbook per BoM with a `BOM Details` sheet plus one sheet per BoM type containing the
  CERT-In minimum-element headers and data rows (HBOM exports include sub-components recursively).

## Getting started

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan serve
```

The app uses [Laravel Herd](https://herd.laravel.com) locally and is served at
`http://<your-site>.test`.

## Usage

- Log in to the Filament admin panel (e.g. `http://bill-of-materials.test/admin`) — demo credentials:
  `test@example.com` / `password`.
- **BOMs**: create / edit a BoM, pick its type (SBOM / QBOM / CBOM / AIBOM / HBOM) and, for SBOMs, choose the
  CERT-In **level** and **classification**.
- Open the BoM detail page and use the relation managers to add / edit the minimum elements of the selected
  BoM type.
- Click **Export XLSX** on the BoM edit page (or via the record action on the BoM list) to download the
  generated workbook (`BOM Details` + per-type minimum-element sheets).

## How each BoM is created (step by step)

### 1. SBOM (Software Bill of Materials) — CERT-In Table 5

1. Go to **Bill of Materials → Boms → Create**.
2. Select a **Product** the SBOM describes.
3. Set **Type** to *SBOM* — the **Level** (Top-level / n-level / Delivery / Transitive / Complete) and
   **Classification** (Design / Source / Build / Analyzed / Deployed / Runtime) fields appear and are required.
4. Fill the BOM details (name, version, specification, status, release date, description, metadata).
5. Open the **Software components** relation and add one row per component with: name, version, description,
   supplier, license, origin, dependencies, vulnerability references, patch status, release / end-of-life dates,
   criticality, usage restrictions, checksum (algorithm + value), author, executable / archive / structured flags,
   unique identifier (purl / CPE).

### 2. QBOM (Quantum Bill of Materials)

1. Create a BoM with **Type** = *QBOM*.
2. Open the **Quantum-vulnerable assets** relation and add the quantum asset: model name, version, vendor,
   origin, license, cryptographic asset type, current algorithm, quantum risk, migration status, target
   algorithm, communication protocol, hardware, software dependencies, environmental impact, vulnerabilities,
   attestations.

### 3. CBOM (Cyber Security / Cryptography BoM)

1. Create a BoM with **Type** = *CBOM*.
2. Open the **Cryptographic assets** relation and add the cryptographic asset.
3. Depending on the selected **Asset type** (Algorithm / Key / Protocol / Certificate / …), the relevant
   minimum elements are shown conditionally (algorithm, primitive, mode, crypto functions, security level, OID,
   keys, protocols, certificates).

### 4. AIBOM (AI Bill of Materials)

1. Create a BoM with **Type** = *AIBOM*.
2. Open the **AI models, datasets and components** relation and add the AI asset: model name, version, type,
   developer, licensing, software dependencies, ML models & algorithms, performance metrics, data source / data
   sets, hardware, security requirements, input / output, intended usage, out-of-scope usage, environmental
   impact, vulnerabilities, attestations, evaluation.

### 5. HBOM (Hardware Bill of Materials)

1. Create a BoM with **Type** = *HBOM*.
2. Open the **Hardware components** relation and add the product component.
3. Optionally link **Parent component** for sub-components (recursive HBOM structure).
4. Fill product details, warranty / AMC, manufacturer / supplier details, serial / model numbers, technical
   specification, technology node, compliance, power supply, license, test result, criticality, end-of-life and
   vulnerabilities.

### 6. Export to XLSX (all BoM types)

1. From the BoM edit page, click **Export XLSX**.
2. The download contains the `BOM Details` sheet followed by one sheet per BoM type containing the CERT-In
   minimum-element headers and data rows (including recursive HBOM sub-components).

## Development

- Database migrations: `database/migrations`
- Seeders (demo BoM data): `database/seeders`
- Exports: `app/Exports/BomXlsxExporter.php`
- Filament actions / forms / tables / relation managers: `app/Filament`

```bash
php artisan migrate:fresh --seed
vendor/bin/pint
php artisan test
php artisan filament:clear-cached-components
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
