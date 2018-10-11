<template>
    <tabs >

        <tab :id="hash" :name="tab.html" v-for="tab in availableTabs" :key="tab.id" >
            <component
              :class="{'remove-bottom-border': index == tab.fields.length - 1}"
              :key="index"
              v-for="(field, index) in tab.fields"
              :is="resolveComponentName(field)"
              :resource-name="resourceName"
              :resource-id="resourceId"
              :resource="resource"
              :field="field"
              @actionExecuted="actionExecuted"
            />
        </tab>

    </tabs>
</template>

<script>

  export default {

    props: ['fields', 'field', 'resourceName', 'resourceId', 'resource'],
    computed: {
      /**
       * Get the available field panels.
       */
      availableTabs() {
          var tabs = {}

          var fields = _.toArray(JSON.parse(JSON.stringify(this.field.fields)))

          fields.forEach(field => {
            if (tabs[field.tab.name]) {
            return tabs[field.tab.name].fields.push(field)
          }

          tabs[field.tab.name] = this.createTabForField(field)
        })

          return _.toArray(tabs)

      }},
      hash() {
        return tab.name.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").replace(/ /g, '-')
      },
    methods: {

      createTabForField(field) {
        return {fields: [field], name: field.tab.name, id: 'tab-'+field.tab.name, html: field.tab.html}
      },
      resolveComponentName(field) {
        return field.prefixComponent ? 'detail-' + field.component : field.component
      },
    }
}
</script>
