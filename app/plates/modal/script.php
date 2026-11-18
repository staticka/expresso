<script>
  const link = '<?php echo $link->set('/pages') ?>'

  const modal =
  {
    error: {},
    name: null,
    description: null,
    link: null,
    loading: false,
    store()
    {
      const input = new FormData

      const self = this

      if (self.name)
      {
        input.append('name', self.name)
      }

      if (self.description)
      {
        input.append('description', self.description)
      }

      if (self.link)
      {
        input.append('link', self.link)
      }

      self.loading = true

      self.error = {}

      axios.post(link, input)
        .then(function (response)
        {
          window.location.href = link
        })
        .catch(function (error)
        {
          self.error = error.response.data

          self.loading = false
        })
    },
  }
</script>
