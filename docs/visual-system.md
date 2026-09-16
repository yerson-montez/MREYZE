# MREYZE · Sistema visual, fase 1

La fuente del sistema es `resources/css/design-system.css`, importada por `app.css`.
Se activa con `class="mreyze-ui"` en el body de los seis layouts utilizados.
Las páginas conservan sus estructuras, selectores, formularios e interacciones.

## Fundamentos

| Uso | Token |
| --- | --- |
| Fondo / fondo alternativo | `--background`, `--background-secondary` |
| Panel / hover | `--surface`, `--surface-hover` |
| Acción principal / hover | `--primary`, `--primary-hover` |
| Acentos | `--accent`, `--cyan` |
| Texto | `--text-primary`, `--text-secondary`, `--text-muted` |
| Texto sobre botón azul | `--text-on-primary` |
| Bordes | `--border`, `--border-hover` |
| Elevación | `--shadow`, `--shadow-raised` |
| Radios | `--radius-sm`, `--radius-md`, `--radius-lg`, `--radius-xl` |
| Tipografía | `--font-sans`, `--font-heading`, `--font-mono` |
| Contenido | `--container-width` (80rem), `--container-narrow` (48rem) |
| Espacios fluidos | `--page-gutter`, `--section-space`, `--grid-gap`, `--card-padding` |
| Escala de espacio | `--space-1`, `2`, `3`, `4`, `6`, `8`, `12`, `16` |

Inter es la fuente principal; Segoe UI y sans-serif son las alternativas locales.
Los encabezados utilizan `clamp()`. Las clases de encabezado definen tamaño visual;
se debe elegir h1/h2/h3 según la jerarquía semántica de la página.
Los botones principales llevan texto oscuro para mantener contraste con el azul.

## Composición

```blade
<x-ui.section aria-labelledby="soluciones-title">
    <x-ui.container class="mreyze-stack">
        <p class="mreyze-eyebrow">Ingeniería aplicada</p>
        <h2 id="soluciones-title" class="mreyze-heading-2">Tecnología que conecta</h2>
        <p class="mreyze-copy">Contenido de la sección.</p>
        <div class="mreyze-grid">
            <x-ui.card class="mreyze-stack">
                <x-ui.badge>IoT</x-ui.badge>
                <h3 class="mreyze-heading-3">Sistemas conectados</h3>
                <p class="mreyze-copy">Descripción de la solución.</p>
            </x-ui.card>
        </div>
    </x-ui.container>
</x-ui.section>
```

`mreyze-container--narrow` limita el ancho para lectura o formularios.
`mreyze-grid` se adapta desde una columna sin imponer anchos mayores al contenedor.
`mreyze-stack` separa hijos; puede personalizarse con `--stack-space`.
No convertir una tarjeta informativa en clicable sin un enlace o botón semántico.

## Componentes existentes

Se conservan los componentes `x-primary-button`, `x-secondary-button`,
`x-danger-button`, `x-text-input`, `x-input-label`, `x-input-error`,
`x-dropdown`, `x-dropdown-link` y `x-modal`.
Solo se añadieron clases visuales; tipos, atributos, slots y código Alpine permanecen.

- Botones: `mreyze-btn`, `mreyze-btn--secondary`, `mreyze-btn--danger`.
- Campos: `mreyze-input`, también aplicable a select y textarea existentes.
- Etiquetas/errores: `mreyze-label`, `mreyze-error`.
- Badges: `mreyze-badge--neutral`, `--success`, `--warning`, `--danger`.
- Dropdown y modal: `mreyze-dropdown-panel`, `mreyze-modal-panel`.

Conservar siempre nombres de campo, valores, URLs, métodos, CSRF, atributos data,
condiciones Blade y manejadores existentes. Un badge solo presenta un estado:
no determina ni transforma el estado del negocio.

## Iconos

Se reutiliza SVG nativo; no se añadió una librería ni un proveedor externo nuevo.
`x-ui.icon` es un contenedor decorativo de 24 × 24 unidades para trazos SVG:

```blade
<x-ui.icon><path d="M5 12h14m-6-6 6 6-6 6" /></x-ui.icon>
```

Para un botón sin texto visible, proporcionar un nombre accesible en el botón.
No usar emojis como iconos en nuevos componentes.

## Compatibilidad y alcance

Los alias `--ice-*` y los tokens de los layouts anteriores apuntan a la nueva paleta.
Los estilos están acotados a `.mreyze-ui`; las plantillas PDF quedan fuera.
No se eliminan CSS antiguos hasta migrar y verificar cada página.
No se fuerzan estados display, x-show, hidden, formularios o controles deshabilitados.
La navegación recibe una capa visual y un límite de alto para desplazamiento móvil;
no se cambian sus opciones ni sus controladores de eventos.

La preferencia de movimiento reducido afecta CSS. Los canvas y temporizadores
existentes permanecen intactos: revisarlos corresponde a una fase posterior.
Las cuatro columnas móviles de tienda, el perfil y otros diseños particulares
no se reconstruyen en esta fase.

## Comprobación

Compilar con `npm run build`. Verificar componentes a 320, 375, 768, 1024 y 1440 px,
teclado, foco, campos inválidos/deshabilitados y apertura/cierre de modales.
La comprobación funcional completa requiere un entorno de aplicación configurado;
un render aislado de Blade no sustituye login, pagos o una petición real a la API.

### Resultado de verificación de esta fase

- `npm run build`: correcto. JavaScript conserva el hash `app-CBbTb_k3.js` de la línea base.
- `php artisan view:cache`: correcto.
- PHPUnit, suite Unit: 6 pruebas y 6 assertions correctas. No se ejecutaron migraciones.
- Render real de Blade, aislado de la base de datos: acceso, registro, recuperación,
  servicios, contacto, blog y una muestra de los componentes.
- Navegador Edge: 35 combinaciones de vista/ancho (320, 375, 768, 1024 y 1440 px).
  Sin errores JavaScript ni respuestas HTTP de error en esos renders.
  La muestra y los formularios de autenticación no desbordan horizontalmente;
  las páginas públicas conservan su CSS anterior de overflow y requieren una
  auditoría particular al rediseñarlas.
- Dropdown y modal: apertura/cierre verificados; foco visible y campos de 16px.
- Formularios de acceso y registro: acciones POST y nombres conservados.
- Comparación de fuentes: scripts, IDs, nombres, métodos, valores, atributos data
  y Alpine de las vistas modificadas coinciden con la versión original.
- Servidor real: `/up` y `/api/test` responden 200. Un envío vacío a `/api/contacto`
  responde 422 por validación, antes de guardar datos o enviar correo.
- `/login`, `/register`, `/tienda` y `/proyectos` responden 500 tanto con las
  plantillas originales como con las nuevas. El error es `No application encryption
  key has been specified.` La copia no dispone de `.env` ni de una base configurada.

La comprobación integral de sesiones, acceso, registro, pedidos, pagos e inversión
queda pendiente de un entorno funcional autorizado. No se crearon claves,
usuarios, tablas ni datos, ni se cambió configuración de producción.
