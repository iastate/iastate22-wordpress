export class SortableTable {
  private element: HTMLElement;
  private headers: NodeListOf<HTMLElement>;
  constructor(element: HTMLElement) {
    if (!!element) {
      this.element = element;
      this.headers = this.element.querySelectorAll("th.sortable");
      this.init();
    }
  }

  private init() {
    this.sortTable();
  }

  private sortTable() {
    this.headers.forEach(function(header, index) {
      header.addEventListener("click", function() {
        sortColumn(index);
      });
    });
    const tableBody = this.element.querySelector("tbody");
    const rows = tableBody.querySelectorAll("tr");
    const sortColumn = function(index) {
      // Clone the rows
      const newRows = Array.from(rows);

      // Sort rows by the content of cells
      newRows.sort(function(rowA, rowB) {
        // Get the content of cells
        const cellA = rowA.querySelectorAll("td")[index].innerHTML;
        const cellB = rowB.querySelectorAll("td")[index].innerHTML;

        switch (true) {
          case cellA > cellB:
            return 1;
          case cellA < cellB:
            return -1;
          case cellA === cellB:
            return 0;
        }
      });

      // Remove old rows
      [].forEach.call(rows, function(row) {
        tableBody.removeChild(row);
      });

      // Append new row
      newRows.forEach(function(newRow) {
        tableBody.appendChild(newRow);
      });
    };
  }
}

export default function sortableTableInit() {
  const tables = document.querySelectorAll(".sortable-table") as NodeListOf<HTMLElement>;
  for (let i = 0; i < tables.length; i++) {
    new SortableTable(tables[i]);
  }
}
