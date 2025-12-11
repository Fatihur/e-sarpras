# Class Diagram e-Sarpras

## Diagram dalam Format PlantUML

```plantuml
@startuml e-Sarpras Class Diagram

skinparam classAttributeIconSize 0
skinparam class {
    BackgroundColor White
    BorderColor Black
    ArrowColor Black
}

' ==================== MODELS ====================

class User {
    +id: bigint
    +nama: string
    +email: string
    +password: string
    +role: enum
    +aktif: boolean
    +remember_token: string
    --
    +isAdmin(): bool
    +isManajemen(): bool
    +isPimpinan(): bool
}

class Lahan {
    +id: bigint
    +kode_lahan: string
    +nama_lahan: string
    +lokasi_lahan: string
    --
    +gedung(): HasMany
}

class Gedung {
    +id: bigint
    +nama_gedung: string
    +alamat_gedung: text
    +lahan_id: bigint
    --
    +lahan(): BelongsTo
    +ruangan(): HasMany
}

class Ruangan {
    +id: bigint
    +kode_ruangan: string
    +nama_ruangan: string
    +gedung_id: bigint
    +penanggung_jawab: string
    +keterangan: text
    --
    