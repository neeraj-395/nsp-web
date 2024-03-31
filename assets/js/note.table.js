function note_tuple(meta){
  return `
  <tbody>
    <tr>
      <td>${meta.note_id}</td>
      <td>${meta.title}</td>
      <td>${meta.upload_date}</td>
      <td>${meta.upload_time}</td>
      <td class="text-end">
        <a href="" class="btn btn-outline-info btn-rounded">
          <i class="bi bi-eye-fill fs-5"></i>
        </a>
        <a href="" class="btn btn-outline-danger btn-rounded">
          <i class="bi bi-trash-fill fs-5"></i>
        </a>
      </td>
    </tr>
  </tbody>`;
}





