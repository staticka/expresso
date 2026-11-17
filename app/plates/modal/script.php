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
          exToast('Page created!', 'success')

          setTimeout(function () { window.location.href = link }, 800)
        })
        .catch(function (error)
        {
          self.error = error.response.data
        })
        .finally(function ()
        {
          self.loading = false
        })
    },
  }
</script>
