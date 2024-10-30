( function ( blocks, element, blockEditor ) {
  const blockProps = blockEditor.useBlockProps;
  const htmlToElem = ( html ) => wp.element.RawHTML( { children: html } );
  const el = wp.element.createElement; // The wp.element.createElement() function to create elements.

  const getCollections = () => {
    let anyEmpty = false;
    jQuery('.koi-select').each(function () {
      if(jQuery(this).has('option').length === 0) {
        anyEmpty = true;
      }
    });

    if (!anyEmpty) {
      return;
    }

    fetch(`${window.koi_url}/embed/collections?token=${window.koi_back_token}`)
    .then(response => response.json())
    .then(data => {
      jQuery('.koi-select').each(function () {
        const selector = jQuery (this);
        if(selector.has('option').length === 0) {
          data.forEach(function (collection) {
            const selected = collection.id === selector.data('collection-id') ? true : false;
            jQuery(this).append(new Option(collection.title, collection.id, selected, selected));
          }.bind(this));
        }
      })
    });
  }

  blocks.registerBlockType( 'koi/collection', {
    attributes: {
      collectionId: {
        type: 'array',
        source: 'children',
        selector: 'div.koi-block-collection',
        default: -1
      }
    },

    edit: props => {

      setTimeout(getCollections, 1000);

      const onChangeContent = event => {
        props.setAttributes( { collectionId: event.target.value } );
      };

      return el(
        'div',
        blockProps({ className: props.className, "data-collection-id": props.attributes.collectionId }),
        htmlToElem(
          '<div style="text-align: center;"><img style="width: 100px; height: auto; margin: 0 auto;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iODA4cHgiIGhlaWdodD0iNDA5cHgiIHZpZXdCb3g9IjAgMCA4MDggNDA5IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogICAgPGcgaWQ9IlBhZ2UtMSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggZD0iTTE2NC43NjU5MywwIEw4MS42NzE3MTgzLDE0Mi40MzA4MDEgQzc3LjM3MDUxNzIsMTQ5LjgwMzQzOCA2OS40NzcyMTExLDE1NC4zMzY4NTYgNjAuOTQxNjMxNSwxNTQuMzM2ODU2IEwyMy4zNTM5NTQsMTU0LjMzNjg1NiBDMTIuMDExMTQwNSwxNTQuMzM2ODU2IDIuNTA2MTczNzMsMTQ2LjQ2ODA5OSAxLjUyMTAwNTU0ZS0xNCwxMzUuODkxNTMyIEw5LjEwMzIzMzc0ZS0xMywzMC43NjY2MjI0IEM5LjA5MDEzMjE5ZS0xMywyMC4wNjgzOTc1IDEuMTEzOTA3MzMsMTYuMTg4OTYyNyAzLjIwNTU5MjQ1LDEyLjI3Nzg1MTYgQzUuMjk3Mjc3NTYsOC4zNjY3NDA0NSA4LjM2Njc0MDQ1LDUuMjk3Mjc3NTYgMTIuMjc3ODUxNiwzLjIwNTU5MjQ1IEMxNi4xODg5NjI3LDEuMTEzOTA3MzMgMjAuMDY4Mzk3NSw3LjIyMjIzNjU5ZS0xNiAzMC43NjY2MjI0LC0xLjI0MzAwODM3ZS0xNSBMMTY0Ljc2NTkzLC04LjY0MjI4MjVlLTE2IFogTTI4MSwtNS4zNTY2NTY5NmUtMTYgTDQ3MC41LDAgQzU4My40NDIyMzEsLTIuMDc0NzE1MTNlLTE0IDY3NSw5MS41NTc3Njg3IDY3NSwyMDQuNSBDNjc1LDMxNy40NDIyMzEgNTgzLjQ0MjIzMSw0MDkgNDcwLjUsNDA5IEwyODEsNDA5IEwxNjguNzE0NjU5LDIxNi41ODA2MDcgQzE2NC4zNTI3MzUsMjA5LjEwNTczMyAxNjQuMzUzMDYsMTk5Ljg2MTM2NCAxNjguNzE1NTExLDE5Mi4zODY3OTYgTDI4MSwwIFogTTE2NC43NjU5Myw0MDkgTDMwLjc2NjYyMjQsNDA5IEMyMC4wNjgzOTc1LDQwOSAxNi4xODg5NjI3LDQwNy44ODYwOTMgMTIuMjc3ODUxNiw0MDUuNzk0NDA4IEM4LjM2Njc0MDQ1LDQwMy43MDI3MjIgNS4yOTcyNzc1Niw0MDAuNjMzMjYgMy4yMDU1OTI0NSwzOTYuNzIyMTQ4IEMxLjExMzkwNzMzLDM5Mi44MTEwMzcgOS4wOTk3NjE4NGUtMTMsMzg4LjkzMTYwMiA5LjA4NjY2MDNlLTEzLDM3OC4yMzMzNzggTDkuMDkxNjc0NTRlLTEzLDI3My4xMDg0NjggQzIuNTA2MTczNzMsMjYyLjUzMTkwMSAxMi4wMTExNDA1LDI1NC42NjMxNDQgMjMuMzUzOTU0LDI1NC42NjMxNDQgTDYwLjk0MTYzMTUsMjU0LjY2MzE0NCBDNjkuNDc3MjExMSwyNTQuNjYzMTQ0IDc3LjM3MDUxNzIsMjU5LjE5NjU2MiA4MS42NzE3MTgzLDI2Ni41NjkxOTkgTDE2NC43NjU5Myw0MDkgWiBNNDcxLjIwNDE4OCwzMjUuMzE0MTIxIEM1MzguMjE1NzAyLDMyNS4zMTQxMjEgNTkyLjUzOTI2NywyNzAuOTU5OTQxIDU5Mi41MzkyNjcsMjAzLjkxMDY2MyBDNTkyLjUzOTI2NywxMzYuODYxMzg0IDUzOC4yMTU3MDIsODIuNTA3MjA0NiA0NzEuMjA0MTg4LDgyLjUwNzIwNDYgQzQwNC4xOTI2NzUsODIuNTA3MjA0NiAzNDkuODY5MTEsMTM2Ljg2MTM4NCAzNDkuODY5MTEsMjAzLjkxMDY2MyBDMzQ5Ljg2OTExLDI3MC45NTk5NDEgNDA0LjE5MjY3NSwzMjUuMzE0MTIxIDQ3MS4yMDQxODgsMzI1LjMxNDEyMSBaIE03NDkuNTI3MTksNDA4Ljg3NTYxNiBDNzQ5LjQ5NDI4Myw0MDguODc1Nzg2IDc0OS40NjEzNzQsNDA4Ljg3NTg4OCA3NDkuNDI4NDY2LDQwOC44NzU5MjIgQzczNi4xNzM2MzksNDA4Ljg4OTgxMiA3MjUuNDE3MjIsMzk4LjE1NTkxMSA3MjUuNDAzMzMsMzg0LjkwMTA4NSBMNzI1LjAyNTE3NSwyNC4wMjUxNDkxIEM3MjUuMDI1MTY3LDI0LjAxNjc2NjEgNzI1LjAyNTE2MiwyNC4wMDgzODMgNzI1LjAyNTE2MiwyNCBDNzI1LjAyNTE2MiwxMC43NDUxNjYgNzM1Ljc3MDMyOCw1Ljk4NzU4NzE4ZS0xNSA3NDkuMDI1MTYyLDAgTDc4NCwwIEM3OTcuMjU0ODM0LC0yLjQzNDg3MzVlLTE1IDgwOCwxMC43NDUxNjYgODA4LDI0IEw4MDgsMzg0LjY5NzM2NiBDODA4LDM5Ny45MDM4NSA3OTcuMzMwMTgyLDQwOC42Mjg4ODIgNzg0LjEyMzg3Myw0MDguNjk3MDQ2IEw3NDkuNTI3MTksNDA4Ljg3NTYxNiBaIiBpZD0iQ29tYmluZWQtU2hhcGUiIGZpbGw9IiM0RjQ2RTUiPjwvcGF0aD4KICAgIDwvZz4KPC9zdmc+" />' +
          '<br /><strong>Koi Collection</strong><div><div id="koi-embed"></div></div>'
        ),
        el(
          'select',
          {
            className: 'koi-select',
            onChange: onChangeContent,
            value: props.attributes.collectionId,
            "data-selected": props.attributes.collectionId
          }
        ),
        el(
          'div',
          {
            className: 'koi-block-collection',
            "data-collection-id": props.attributes.collectionId
          },
          props.attributes.collectionId
        )
      )
    },
    save: props => {
      return el(
        'div',
        wp.blockEditor.useBlockProps.save({ className: props.className }),
        el(
          'div',
          {
            className: 'koi-block-collection',
          },
          props.attributes.collectionId
        ),
        htmlToElem(
          '<div class="koi-collection" data-collection-id="' +  props.attributes.collectionId + '"></div>'
        )
      )
    }
  } );
} )( window.wp.blocks, window.wp.element, window.wp.blockEditor );